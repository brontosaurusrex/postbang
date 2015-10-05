bl_info = {
    "name": "Batch Renamer",
    "description": "A set of tools for dealing with many objects with similar names",
    "author": "Greg Zaal",
    "version": (1, 3),
    "blender": (2, 74, 0),
    "location": "Properties Editor > Object",
    "warning": "",
    "wiki_url": "",
    "tracker_url": "",
    "category": "Object"}

import bpy
from bpy.types import Operator, Panel
from math import ceil, log10

'''
    Tools:
        Select/Deselect with similar name (button for each namespace 'pillar*', 'pillar_stack*', 'pillar_stack_big*')
        show/hide names

    TODO:
        Suffix & prefix
        Find & replace
        Support for bone renaming
            Sort by chain
            Add L/R mirror name
        Sort by distance (cursor, object, center of selection)
        Remove panel, make a popup like cell fracture, append to objects menu in 3d view and toolbar
'''

def do_rename(context):
    return_message = ""

    counter = context.scene.RNStartNumber
    numobjs = counter
    presuffix=""
    suffix=""
    name = context.scene.RNNewName
    separator = context.scene.RNSeparator
    padding = (context.scene.RNPadding)*-1
    padtype=context.scene.RNPaddingType
    oldname=""
    
    #run once to change all names and make sure of no duplicates in the next run
    for obj in context.selected_objects:
        obj.name="0n3R3d1cul0uslyUnl1kelyN4me"
        if obj.data.users == 1:
            obj.data.name = "0n3R3d1cul0uslyUnl1kelyN4me"
        numobjs=numobjs+1
        
    #auto padding
    if padtype=='auto':
        padding=-1
    
    required_padding = ceil(log10(numobjs))
    if padtype=='auto':
        padding = -1*required_padding # automatic padding
    elif padtype == 'manual' and required_padding > context.scene.RNPadding:
        padding = -1*required_padding
        return_message = ({'WARNING'}, "Warning! Padding set too low, automatically increasing.")
        
    
    sort_type = context.scene.RNSortType
    sort_axis = context.scene.RNSortAxis
    sort_reverse = context.scene.RNSortReverse
    objects=context.selected_objects
    
    if sort_type == 'none':
        pass
    elif sort_type == 'location':
        if sort_axis == 'x':
            objects.sort(key=lambda srt: srt.location.x, reverse=sort_reverse)
        elif sort_axis == 'y':
            objects.sort(key=lambda srt: srt.location.y, reverse=sort_reverse)
        elif sort_axis == 'z':
            objects.sort(key=lambda srt: srt.location.z, reverse=sort_reverse)
        elif sort_axis == 'sum':
            objects.sort(key=lambda srt: srt.location.x + srt.location.y + srt.location.z, reverse=sort_reverse)
        
    elif sort_type == 'scale':
        if sort_axis == 'x':
            objects.sort(key=lambda srt: srt.scale.x, reverse=sort_reverse)
        elif sort_axis == 'y':
            objects.sort(key=lambda srt: srt.scale.y, reverse=sort_reverse)
        elif sort_axis == 'z':
            objects.sort(key=lambda srt: srt.scale.z, reverse=sort_reverse)
        elif sort_axis == 'sum':
            objects.sort(key=lambda srt: srt.scale.x + srt.scale.y + srt.scale.z, reverse=sort_reverse)
        
    elif sort_type == 'dimension':
        if sort_axis == 'x':
            objects.sort(key=lambda srt: srt.dimensions.x, reverse=sort_reverse)
        elif sort_axis == 'y':
            objects.sort(key=lambda srt: srt.dimensions.y, reverse=sort_reverse)
        elif sort_axis == 'z':
            objects.sort(key=lambda srt: srt.dimensions.z, reverse=sort_reverse)
        elif sort_axis == 'sum':
            objects.sort(key=lambda srt: srt.dimensions.x + srt.dimensions.y + srt.dimensions.z, reverse=sort_reverse)
        
                    
    for obj in objects:
        #obj.show_name=True    # for debuging
        if padtype=='none':
            presuffix=str(counter)
        else:
            presuffix=("000000000000000000000"+str(counter))[padding:]
        suffix=separator+presuffix
        n = name+suffix
        obj.name = n  # rename
        if obj.data.users == 1:
            obj.data.name = n
        counter=counter+1
    
    if context.scene.RNContinuedNums:
        context.scene.RNStartNumber += len(context.selected_objects)

    return return_message

class RNBatchRename(bpy.types.Operator):
    'Renames all selected objects from the base-name'
    bl_idname='rename.rename'
    bl_label='Rename'
    
    def execute(self,context):
        
        message = do_rename(context)

        if message:
            self.report(message[0], message[1])
            
        return {'FINISHED'}

class RNGetActivename(bpy.types.Operator):
    'Sets the new base-name to the base-name of the active object'
    bl_idname='rename.get_active'
    bl_label='Get Active Base-name'
    
    def execute(self,context):        
        name=context.active_object.name
        
        # remove any numbering
        base_dot = name[::-1].split('.', maxsplit=1)[-1][::-1]
        base_under = name[::-1].split('_', maxsplit=1)[-1][::-1]
        base_dash = name[::-1].split('-', maxsplit=1)[-1][::-1]
        base_sep = name[::-1].split((context.scene.RNSeparator[::-1]), maxsplit=1)[-1][::-1]
        strings=[base_dot, base_under, base_dash, base_sep]
        shortest_base=min(strings, key=len)
        context.scene.RNNewName=shortest_base
        return {'FINISHED'}

class RNToggleNameLabel(bpy.types.Operator):
    'Show or hide the name label in the 3D View for selected objects'
    bl_idname='rename.toggle_label'
    bl_label='Toggle Labels'
    
    def execute(self,context):        
        objects = context.selected_editable_objects

        state = 0 < len([obj for obj in objects if obj.show_name == True])

        for obj in objects:
            obj.show_name = not state

        return {'FINISHED'}
    

class RNBatchRenamePanel(bpy.types.Panel):
    bl_label = "Batch Rename"
    bl_space_type = "PROPERTIES"
    bl_region_type = "WINDOW"
    bl_context = "object"

    def draw_main(self, scene, layout):
        col = layout.column()
        row=col.row(align=True)
        row.prop(scene, "RNExpandMain", icon="TRIA_DOWN" if scene.RNExpandMain else "TRIA_RIGHT", emboss=False, text="")
        row.separator()
        row.operator("rename.get_active", icon="EXPORT", text="")
        row.prop(scene, "RNNewName", icon="GREASEPENCIL", text="")
        row.operator("rename.rename")
        if scene.RNExpandMain:
            split=col.split()
            split.label(text="Separator:")
            split.prop(scene, "RNSeparator", text="")
            split=col.split()
            split.label(text="Padding:")
            row=split.row()
            row.prop(scene, "RNPaddingType", expand=True)
            if scene.RNPaddingType=='manual':
                split=col.split()
                split.label(text="")
                split.prop(scene, "RNPadding", text="Padding")
            split=col.split()
            split.label(text="Start Number:")
            row=split.row(align=True)
            row.prop(scene, "RNStartNumber", text="")
            row.prop(scene, 'RNContinuedNums', toggle=True, text='', icon='DOTSDOWN')
            
            row=col.row(align=True)
            row.label(text='Sorting:')
            row.prop(scene, 'RNSortType', text='')
            row.prop(scene, 'RNSortAxis', text='')
            row.prop(scene, 'RNSortReverse', text='', icon='ARROW_LEFTRIGHT', toggle=True)

    def draw_tools(self, scene, layout):
        col = layout.column()
        row = col.row(align=True)

        row.prop(scene, 'RNExpandTools', icon="TRIA_DOWN" if scene.RNExpandTools else "TRIA_RIGHT", emboss=False, text="")
        row.separator()
        row.label("Tools:")
        row.operator('rename.toggle_label')
 
    def draw(self, context):
        scene=context.scene
        layout=self.layout
        
        maincol=layout.column()

        self.draw_main(scene, maincol.box())
        # self.draw_tools(scene, maincol.box())  # WIP

        # for debugging    
        # objs=bpy.context.selected_objects
        # objs.sort(key=lambda srt: srt.name)
        # for obj in objs:
        #     col.label(obj.name)
      
def register():
    bpy.types.Scene.RNNewName = bpy.props.StringProperty(
        name="New Name",
        default="base_name",
        description="The new base-name for the selected objects")
    
    bpy.types.Scene.RNSeparator = bpy.props.StringProperty(
        name="Separator",
        default="_",
        description="The bit between the base name and incremented number")
    
    bpy.types.Scene.RNPadding = bpy.props.IntProperty(
        name="Separator",
        default=2,
        min=1,
        max=20,
        description="Force a minimum number of digits for the incremented number")
    
    bpy.types.Scene.RNStartNumber = bpy.props.IntProperty(
        name="Start Number",
        default=1,
        min=1,
        description="The lowest number (where to start counting from)")
    
    bpy.types.Scene.RNPaddingType = bpy.props.EnumProperty(
        name="Padding Type",
        items=(("auto","Auto","Automatically determine padding"),("manual","Manual","Manually choose padding"),("none","None","No padding")),
        default='auto',
        description="The type of padding")
    
    bpy.types.Scene.RNSortType = bpy.props.EnumProperty(
        name="Sorting",
        items=(("none","None","Use default list order (not in any order)"),("location","Location","Sort by the location of objects on a certain axis"),("scale","Scale","Sort by the scale of objects on a certain axis"),("dimension","Dimension","Sort by the dimension of objects on a certain axis")),
        default='location',
        description="In what order to rename the objects")
    
    bpy.types.Scene.RNSortAxis = bpy.props.EnumProperty(
        name="Sort Axis",
        items=(("sum","All","Order by the sum of all three axes"),("x","X","Order by the X value"),("y","Y","Order by the Y value"),("z","Z","Order by the Z value")),
        default='x',
        description="Which axis to sort by")
    
    bpy.types.Scene.RNSortReverse = bpy.props.BoolProperty(
        name="Reverse",
        default=False,
        description="Reverse the order of the sorting")
    
    bpy.types.Scene.RNContinuedNums = bpy.props.BoolProperty(
        name="Continued Numbering",
        default=False,
        description="Remember where numbering ended and start again from there each time (manual padding recommended)")
    
    bpy.types.Scene.RNExpandMain = bpy.props.BoolProperty(
        name="Advanced",
        default=False,
        description="Show extra renaming options")
    
    bpy.types.Scene.RNExpandTools = bpy.props.BoolProperty(
        name="Advanced",
        default=False,
        description="Show tools")
    
    bpy.utils.register_module(__name__)
    
def unregister():
    del bpy.types.Scene.RNNewName
    del bpy.types.Scene.RNSeparator
    del bpy.types.Scene.RNPadding
    del bpy.types.Scene.RNStartNumber
    del bpy.types.Scene.RNPaddingType
    del bpy.types.Scene.RNSortType
    del bpy.types.Scene.RNSortAxis
    del bpy.types.Scene.RNSortReverse
    del bpy.types.Scene.RNContinuedNums
    del bpy.types.Scene.RNExpandMain
    del bpy.types.Scene.RNExpandTools
    
    bpy.utils.unregister_module(__name__)
    
if __name__ == "__main__":
    register() 