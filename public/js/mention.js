$q().on('keyup', '.post-body, .reply-body', (ev)=>{
  if(ev.key === '@') {
    let input = ev.target;
    let beforeAt = input.value.substr(0, (input.selectionStart - 1));
    let afterAt = input.value.substr(input.selectionStart);
    let mention = '';
    let tagModal = new Modal({
      id: 'tag-modal',
      noTitle: true,
      noButtons: true,
      closeOnEsc: true,
      closeOnOverlayClick: true,
      content: '<input type="text" id="tag-name" placeholder="Select user to tag..." /><input type="hidden" id="tag-id" />',
      open: () => {
        $q('#tag-name').focus();
        new Autocomplete({
          minimum: 1,
          searchPlaceholder: '???',
          url: '/corkboard/users/searchByName/???',
          input: '#tag-name',
          target: '#tag-id',
          handleQueryData: (data) => {
            return data.users.map((u) => ({ id: u.username, display: u.name }));
          },
          handleSelectItem: (selected) => {
            mention = '@'+ selected.id+' ';
            tagModal.destroy();
          }
        });
      },
      close: () => {
        let cursorPos = beforeAt.length + mention.length;
        input.val(beforeAt + mention + afterAt);
        input.focus();
        input.setSelectionRange(cursorPos, cursorPos);
      }
    });
  }
});
