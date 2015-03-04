      var player = null;

      function playerReady(obj)
      {
        player = gid(obj.id);
        player.addModelListener('STATE', 'stateMonitor');
      };

      function stateMonitor(obj)
      {
        if(obj.newstate == 'COMPLETED')
        {
          // drop out of fullscreen
         //this.window.focus();

          // load a new page
         self.location = next;
        }
      };

      function gid(name)
      {
        return document.getElementById(name);
      };
