(function simpleTodo() {
  var app = new DeskApp(),
      items = [];
  app.get('items', function(data) {
    items = (data) ? data.split(';;') : [];
    $('.status').html(items.length > 0 ? "" : "<h1>No Items!</h1>");
    for (var i=0 , max = items.length; i < max; i++) {
      addItem(items[i]);
    }
    bind();
  });

  function addItem(txt) {
    console.log(txt);
    $('ul').append($('<li><input type="checkbox"/><span class="desc">' + txt + '</span></li>'));
  }

  function store() {
    app.set('items', (items.length > 1) ? items.join(';;') : items[0]);
  }

  var addHandler = function() {
    var txt = $('.newtext')[0].value;
    if (txt.length > 0) {
      addItem(txt);
      items.push(txt);
      store();
      $('.status').html('');
    }
    $('.newtext')[0].value = '';
  }

  var bind = function() {

    $('ul').on('click', 'li input', function() {
      var checked = ($(this)[0].checked);
      var line = $(this).closest('li');
      items.splice(line.index(), 1);
      store();
      if (checked) {
        line.find('.desc').addClass('checked');

        setTimeout(function() {
          line.animate({opacity: 0}, 500, function() {
            line.remove();

            if (items.length == 0) {
              $('.status').html("<h1>No Items!</h1>");
            }
          });
        }, 500);
      }
    });
  }

  $('button').bind({
    'click': addHandler
  });

  $('.newtext').bind({
    'keypress' : function(e) {
      if (e.keyCode == 13) addHandler();
    }
  });

})();