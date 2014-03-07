var app,
    maxLength = 20;

$(function() {

  app = new DeskApp();

  $("#home").bind("click", function() {
    app.showSection("list");
  });

  for(var i=1; i<=maxLength; i++){
    $("#articleList").append('<li id="list' + i + '" onclick="app.showSection(\'article'+i+'\')"><a href="#article' + i + '" id="link' + i + '">&nbsp;</a></li>');
    var prevdisabled = (i==1) ? "disabled" : "",
        nextdisabled = (i==maxLength) ? "disabled" : "";
    $("body").append(
      '<section id="article' + i + '">' +
      '  <footer>' +
      '    <button id="home" onclick="app.showSection(\'list\')">home</button>' +
      '    <menu class="right">' +
      '      <button onclick="app.showSection(\'article'+String(i-1)+'\')" '+prevdisabled+'>Prev</button>' +
      '      <button onclick="app.showSection(\'article'+String(i+1)+'\')" '+nextdisabled+'>Next</button>' +
      '      <button><a id="openButton' + i + '" target="_blank">Open</a></button>' +
      '    </menu>' +
      '  </footer>' +
      '  <div id="articleContent' + i + '"> '+
      '    <h1 id="articleHeader' + i + '">&nbsp;</h1>' +
      '  </div>' +
      '</section>'
    );
  }

  holadeskUI();
  var feed = 'http://feeds.feedburner.com/hacker-news-feed-200?format=xml';
  getOnlineFeed(feed);
/*
  getOnlineFeed('http://news.google.com/news?hl=ja&ned=us&ie=UTF-8&oe=UTF-8&output=atom&topic=h');
*/
  setInterval(function() {
    getOnlineFeed(feed);
  }, 600000);
});

var listEntries = function(json) {
  json = json.value.items[0].channel.item;
  var articleLength = json.length;
  articleLength = (articleLength > maxLength) ? maxLength : articleLength;
  for (var i = 1; i <= articleLength ; i++) {
    var entry = json[i-1];
    $('#link' + i).text(entry.title);
    $('#articleHeader' + i).text(entry.title);
    $('#openButton' + i).attr('href', entry.link);
    $('#articleContent' + i).append(entry["description"]);
  }
  $('#article1 .prevButton').remove();
  $('#article' + articleLength + ' .nextButton').remove();
  if (articleLength < maxLength) {
    for (i = articleLength + 1; i <= maxLength; i++) {
      $('#list' + i).remove();
      $('#article' + i).remove();
    }
  }
};
function getOnlineFeed(url) {
  /*var script = document.createElement('script');
  script.setAttribute('src', 'http://ajax.googleapis.com/ajax/services/feed/load?callback=listEntries&hl=ja&output=json-in-script&q='
                      + encodeURIComponent(url)
                      + '&v=1.0&num=' + maxLength);
  script.setAttribute('type', 'text/javascript');
  document.documentElement.firstChild.appendChild(script);*/
  var script = document.createElement('script');
  script.setAttribute('src', 'https://pipes.yahoo.com/pipes/pipe.run?_id=DJEg41Ac3BG8IAI2E5PZnA&_render=json&url='
                      + encodeURIComponent(url)
                      + '&_callback=listEntries');
  script.setAttribute('type', 'text/javascript');
  document.documentElement.firstChild.appendChild(script);
};
getOfflineFeed = function(url) {
  var script = document.createElement('script');
  script.setAttribute('src', url);
  script.setAttribute('type', 'text/javascript');
  document.documentElement.firstChild.appendChild(script);
};