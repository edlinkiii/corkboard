function setReaction(postId, reactionId=0) {
  let url = REACTION_URL + postId + ((reactionId) ? ('/'+reactionId) : '');
  ajax({
    url: url,
    callback: (data) => updateReactionTotal(data),
  });
}

function updateReactionTotal(data) {
  $q("article#post_id-" + data.post_id + " .reaction-total").text(' '+(data.total || 0));
}

function likeListener(e) {
  let container = e.target.parents(".reaction-panel")[0];
  let postId = container.parent().parent().attr("id").substring(8);
  let reactionContainer = $q('article#post_id-' + postId + ' .reaction-holder');
  console.log(reactionContainer)
  container.remove();
  reactionContainer.removeClass(UNTOUCHED_COLOR_CLASS).addClass(LIKE_COLOR_CLASS);
  setReaction(postId, LIKE_ID);
}

function dislikeListener(e) {
  let container = e.target.parents('.reaction-panel')[0];
  let postId = container.parent().parent().attr('id').substring(8);
  container.remove();
  let reactionContainer = $q('article#post_id-' + postId + ' .reaction-holder');
  reactionContainer.removeClass(UNTOUCHED_COLOR_CLASS).addClass(DISLIKE_COLOR_CLASS);
  reactionContainer.find(ICON_TAG).removeClass(UNTOUCHED_ICON_CLASS).addClass(DISLIKE_ICON_CLASS);
  setReaction(postId, DISLIKE_ID);
}

function clickReactionListener(e) {
  let container = e.target.parent();
  let postId = container.parent().parent().attr('id').substring(8);

  if (container.hasClass(LIKE_COLOR_CLASS) || container.hasClass(DISLIKE_COLOR_CLASS)) {
    if (container.hasClass(DISLIKE_COLOR_CLASS)) {
      container
        .find(ICON_TAG)
        .removeClass(DISLIKE_ICON_CLASS)
        .addClass(LIKE_ICON_CLASS);
    }

    container
      .removeClass(LIKE_COLOR_CLASS)
      .removeClass(DISLIKE_COLOR_CLASS)
      .addClass(UNTOUCHED_COLOR_CLASS);

    setReaction(postId);
  } else {
    if ($q('article#post_id-' + postId + ' .reaction-panel')) {
      // panel exists, close it
      $q('article#post_id-' + postId + ' .reaction-panel').remove();
    } else {
      // create panel
      let targetSelector = 'article#post_id-' + postId + ' .reaction-holder';
      let reactionPanel = new Panel({
        target: targetSelector,
        attachToElement: targetSelector,
        width: 62,
        height: 38,
        addClass: 'reaction-panel',
      });
      let reactionPanelEl = reactionPanel.instance;
      $q(targetSelector).parentNode.appendChild(reactionPanelEl);
      reactionPanelEl.html('<div class="reaction-panel-container"><span class='+LIKE_COLOR_CLASS+'><'+ICON_TAG+' class="'+ICON_CLASS+' '+LIKE_ICON_CLASS+'"></'+ICON_TAG+'></span>&nbsp;&nbsp;<span class='+DISLIKE_COLOR_CLASS+'><'+ICON_TAG+' class="'+ICON_CLASS+' '+DISLIKE_ICON_CLASS+'"></'+ICON_TAG+'></span></div>');
      reactionPanel.show();
      $q('article#post_id-' + postId + ' .reaction-panel '+ICON_TAG+'.'+LIKE_ICON_CLASS).addEventListener("click", likeListener, { once: true });
      $q('article#post_id-' + postId + ' .reaction-panel '+ICON_TAG+'.'+DISLIKE_ICON_CLASS).addEventListener("click", dislikeListener, { once: true });
    }
  }
}

$qa(".reaction-holder").forEach((el) => {
  el.addEventListener("click", clickReactionListener);
});
