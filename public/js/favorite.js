function favoriteClickListener(target) {
  if(MY_USER_ID === '0' || MY_USER_NAME === '') {
    location.href = URLBASE+'/users/login';
    return;
  }
  let isFavoritesView = window.location.href.includes('favorites');
  let post_id = target.parents('article')[0].attr('id').substr(8);
  let icon = $q('article#post_id-'+post_id+' a.favorite-button i');
  let url = FAVORITES_URL + '/' + ((icon.hasClass(FAVORITED_ICON_CLASS)) ? 'remove' : 'add') + '/' + post_id;

  ajax({
    url: url,
    callback: (data) => {
      if(data.Error) {
        $q('article#post_id-'+post_id+' a.favorite-button i');
      }
      else if(data.result) {
        if(icon.hasClass(FAVORITED_ICON_CLASS)) {
          icon.removeClass(FAVORITED_ICON_CLASS).addClass(UNFAVORITED_ICON_CLASS);
          if(isFavoritesView) {
            $q('article#post_id-'+post_id).remove();
          }
          else {
            icon.parent().find('span').text(' Favorite');
          }
        }
        else {
          icon.removeClass(UNFAVORITED_ICON_CLASS).addClass(FAVORITED_ICON_CLASS);
          icon.parent().find('span').text(' Unfavorite');
        }
      }
    }
  });
}

$q('main section').on('click', 'article a.favorite-button b *', ({target}) => favoriteClickListener(target));
