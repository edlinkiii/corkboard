class MyUI {
  instance = null;
  targetElement = null;
  config = {
      type: 'div',
      class: 'my-ui',
      display: 'block'
  }
  settings = {
      id: null,
      classes: [],
      target: 'body',
      open: function() {},
      close: function() {}
  }
  constructor() {
      this.targetElement = this.defineTargetElement(this.settings.target);
  }
  create() {
      this.instance = document.createElement(this.config.type);
      if(this.settings.id) this.instance.id = this.settings.id;
      this.instance.classList.add(this.config.class);
      this.settings.classes.forEach((c) => { this.instance.classList.add(c); });
      this.settings.open();

      return this;
  }
  destroy() {
      this.settings.close();
      this.instance.remove();

      return this;
  }
  show() {
      this.instance.style.display = this.config.display;

      return this;
  }
  hide() {
      this.instance.style.display = 'none';

      return this;
  }
  setting(setting) {
      this.settings = { ...this.settings, setting };

      for(let i in setting) {
          this.instance.style[i] = setting[i];
      }

      return this;
  }
  defineTargetElement(selector) {
      let elem;
      this.selector = selector || 'body';
      if (this.selector === 'document') {
          elem = [document];
      } else if (this.selector === 'window') {
          elem = [window];
      } else if (typeof this.selector === 'object' && typeof this.selector.querySelector === 'function') {
          elem = this.selector;
      } else {
          elem = document.querySelector(this.selector);
      }
      return this.elem = elem;
  }
  findTopZindex() {
      let top = 1;
      document.querySelectorAll('*').forEach((elem) => {
          let css = window.getComputedStyle(elem);
          if(css.display != 'none' && css.visibility != 'invisible') {
              let z = parseInt(css.zIndex);
              if(z) {
                  top = (z > top) ? z : top;
              }
          }
      });
      return top;
  }
}

class Button extends MyUI {
  config = {
      type: 'button',
      class: 'button-ui',
      display: 'inline-block'
  }
  settings = {
      id: null,
      text: 'Button',
      target: 'body',
      classes: [],
      onClick: function() {}
  }
  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      this.config.class = 'button-ui'
      this.targetElement = this.defineTargetElement(this.settings.target);
      this.create();
  }

  create() {
      this.instance = document.createElement(this.config.type);

      if(this.settings.id) {
          this.instance.id = this.settings.id;
      }
      this.instance.classList.add(this.config.class);
      this.settings.classes.forEach((c) => { this.instance.classList.add(c); });
      this.instance.appendChild(document.createTextNode(this.settings.text));
      this.instance.addEventListener("click", e => this.settings.onClick());

      this.targetElement.appendChild(this.instance);

      return this;
  }

  destroy() {
      this.instance.removeEventListener("click", e => this.settings.onClick());
      this.instance.remove();

      return this;
  }

  enable() {
      this.instance.removeAttribute('disabled');

      return this;
  }

  disable() {
      this.instance.setAttribute('disabled','disabled');
  
      return this;
  }
}

class Overlay extends MyUI {
  settings = {
      id: null,
      classes: [],
      backgroundColor: '#000',
      opacity: .5,
      closeOnClick: false,
      closeOnEsc: false,
      target: 'body',
      open: function() {},
      close: function() {}
  }

  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      this.config.class = 'overlay-ui'
      this.topZindex = this.findTopZindex();
      this.targetElement = this.defineTargetElement(this.settings.target);
      this.create();
  }

  create() {
      this.topZindex+=1;
      this.instance = document.createElement(this.config.type);
      if(this.settings.id) this.instance.id = this.settings.id;
      this.instance.classList.add(this.config.class);
      this.settings.classes.forEach((c) => { this.instance.classList.add(c); });
      this.instance.style.backgroundColor = this.settings.backgroundColor;
      this.instance.style.backgroundColor = 'rgba('+ this.instance.style.backgroundColor.substring(4,(this.instance.style.backgroundColor.length-1)) +', '+ this.settings.opacity +')';
      this.instance.style.zIndex = this.topZindex;

      if(this.settings.target !== 'body') {
          this.instance.style.width = this.targetElement.offsetWidth + 'px';
          this.instance.style.height = this.targetElement.offsetHeight + 'px';
      }

      this.targetElement.appendChild(this.instance);

      if(this.settings.closeOnEsc) {
          document.addEventListener("keydown", e => this.handleKeydownEvent(e));
      }

      if(this.settings.closeOnClick) {
          this.instance.addEventListener("click", e => this.destroy());
      }

      this.settings.open();

      return this;
  }

  destroy() {
      this.instance.removeEventListener("keydown", e => this.handleKeydownEvent(e));
      this.instance.removeEventListener("click", e => this.destroy());

      this.settings.close();

      this.instance.remove();

      return this;
  }

  handleKeydownEvent(e) {
      if(e.keyCode === 27) {
          this.destroy();
      }
  }
}

class Blocker extends MyUI {
  settings = {
      target: 'body',
      message: '',
      classes: [],
      css: {
          backgroundColor: 'transparent',
          color: 'black',
          border: 'transparent'
      },
      overlay: {
          target: 'body',
          classes: ['blocker-overlay'],
          backgroundColor: '#fff',
          opacity: 0.4
      },
      open: function() {},
      close: function() {}
  }

  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      this.overlay = new Overlay(this.settings.overlay);
      this.config.class = 'blocker-ui'
      this.topZindex = this.overlay.topZindex;
      this.targetElement = this.overlay.instance;
      this.create();
  }

  create() {
      this.topZindex+=1;
      this.instance = document.createElement(this.config.type);
      this.instance.classList.add(this.config.class);
      this.settings.classes.forEach((c) => { this.instance.classList.add(c); });
      for(let s in this.settings.css) {
          this.instance.style[s] = this.settings.css[s];
      }
      this.instance.style.zIndex = this.topZindex;
      this.instance.innerHTML = this.settings.message;

      this.targetElement.appendChild(this.instance);

      this.settings.open();

      return this;
  }

  destroy() {
      this.settings.close();
      this.instance.remove();
      this.overlay.destroy();

      return this;
  }

  show() {
      this.instance.style.display = this.config.display;
      this.overlay.style.display = this.config.display;
      return this;
  }

  hide() {
      this.instance.style.display = 'none';
      this.overlay.style.display = 'none';
      return this;
  }
}

class Modal extends MyUI {
  config = {
      type: 'div',
      class: 'modal-ui',
      display: 'block'
  }

  settings = {
      id: null,
      target: 'body',
      autoCreate: true,
      title: '',
      noTitle: false,
      draggable: false,
      content: '',
      classes: [],
      width: 300,
      height: 'auto',
      darkMode: false,
      closeOnEsc: false,
      closeOnOverlayClick: false,
      overlay: {
          classes: ['modal-overlay'],
          backgroundColor: '#000',
          opacity: 0.4
      },
      noButtons: false,
      buttons: [
          {
              id: '',
              text: 'Close',
              classes: ['button-red','button-close'],
              onClick: function() {
                  // this.close() // --- ???
              }
          }
      ],
      open: function() {},
      close: function() {}
  }

  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      this.settings.overlay.target = this.settings.target;
      this.overlay = new Overlay(this.settings.overlay);
      this.topZindex = this.overlay.topZindex;
      this.topZindex+=1;
      this.targetElement = this.overlay.instance;
      if(this.settings.autoCreate) this.create();
  }

  create() {
      this.instance = document.createElement(this.config.type);
      this.instance.id = this.settings.id;

      this.instance.classList.add(this.config.class);
      this.settings.classes.forEach((c) => { this.instance.classList.add(c); });
      if(this.settings.darkMode) this.instance.classList.add('dark-mode');
      if(this.settings.noTitle && this.settings.noButtons) this.instance.classList.add("content-only");
      else if(this.settings.noTitle) this.instance.classList.add("no-title");
      else if(this.settings.noButtons) this.instance.classList.add("no-buttons");
      this.instance.setAttribute('style', 'max-width: 100vw; max-height: 100vh; width: '+ this.settings.width +'px; height: '+ ((this.settings.height == parseInt(this.settings.height)) ? this.settings.height+'px' : this.settings.height));
      this.instance.style.zIndex = this.topZindex;

      // this.instance.innerHTML = this.settings.message;

      // modal titlebar
      if(!this.settings.noTitle) {
          this.titleBar = document.createElement('div');
          let title = document.createTextNode(this.settings.title);
          this.titleBar.appendChild(title);
          this.titleBar.classList.add('modal-title');
          this.instance.appendChild(this.titleBar);
          if(this.settings.draggable) this.titleBar.classList.add('draggable');
      }

      // modal content
      let content = this.modalContent = document.createElement('div');
      content.classList.add('modal-content');
      content.innerHTML = this.settings.content;
      this.instance.appendChild(content);

      // modal buttonbar
      if(!this.settings.noButtons) {
          let buttonBar = document.createElement('div');
          buttonBar.classList.add('modal-buttons');
          this.instance.appendChild(buttonBar);

          // modal buttons
          this.settings.buttons.forEach((button) => buttonBar.appendChild((new Button(button)).instance));
      }

      // close on esc
      if(this.settings.closeOnEsc) document.addEventListener("keydown", e => { if(e.keyCode === 27) this.destroy(); });

      // close when overlay is clicked
      if(this.settings.closeOnOverlayClick) this.overlay.instance.addEventListener("click", e => { if(e.target === this.overlay.instance) this.destroy(); });

      if(!this.settings.noTitle && this.settings.draggable) this.drag();

      this.targetElement.appendChild(this.instance);
      this.settings.open();

      return this;
  }

  destroy() {
      this.settings.close();
      this.instance.remove();
      this.overlay.destroy();

      return this;
  }

  show() {
      this.instance.style.display = this.config.display;
      this.overlay.show();

      return this;
  }

  hide() {
      this.instance.style.display = 'none';
      this.overlay.hide();

      return this;
  }

  drag() {
      let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
      this.titleBar.onmousedown = e => {
          e = e || window.event;
          e.preventDefault();
          pos3 = e.clientX;
          pos4 = e.clientY;
          document.onmouseup = () => {
              document.onmouseup = null;
              document.onmousemove = null;
          };
          document.onmousemove = e => {
              e = e || window.event;
              e.preventDefault();
              pos1 = pos3 - e.clientX;
              pos2 = pos4 - e.clientY;
              pos3 = e.clientX;
              pos4 = e.clientY;
              this.instance.style.top = (this.instance.offsetTop - pos2) + "px";
              this.instance.style.left = (this.instance.offsetLeft - pos1) + "px";
          };
      };
  }

  fadeIn() {}

  fadeOut () {}

  content(content) {
      this.modalContent.innerHTML = content;

      return this;
  }
}

class Panel extends MyUI {
  config = {
      type: 'div',
      class: 'panel-ui',
      display: 'block'
  }
  settings = {
      attachToElement: null,
      autoCreate: true
  }
  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      // this.settings.overlay.target = this.settings.target;
      this.topZindex = (this.findTopZindex())+1;
      this.targetEl = this.defineTargetElement(this.settings.target);
      if(this.settings.autoCreate) this.create();
  }
  create() {
      // make input element's parent `position: relative` if position not set
      if(this.targetEl.parentNode.style.position === '') {
          this.targetEl.parentNode.style.position = 'relative';
      }

      this.targetElement = {
          positionX: this.targetEl.offsetLeft,
          positionY: this.targetEl.offsetTop,
          width: this.targetEl.offsetWidth,
          height: this.targetEl.offsetHeight
      };

      this.instance = document.createElement(this.config.type);
      this.instance.classList.add(this.config.class);
      this.instance.classList.add(this.settings.addClass);
      this.instance.style.display = 'none';
      this.instance.style.width = (this.settings.width || this.targetElement.width) +'px';
      this.instance.style.maxHeight = (this.settings.height || 150) +'px';
      this.instance.style.top = (this.targetElement.positionY + this.targetElement.height)+'px';
      this.instance.style.left = this.targetElement.positionX+'px';
      this.instance.style.zIndex = 1000;

      return this;
  }
  destroy() {}
}

class Autocomplete extends MyUI {
  config = {
      class: 'autocomplete-panel-ui'
  }
  settings = {
      minimum: 2,
      matchRequired: true,
      input: null,
      target: null,
      width: null,
      height: null,
      url: null,
      choices: null,
      autoCreate: true,
      handleSelectItem: function(e) { return e; },
      handleQueryData: function(data) { return data; }
  }
  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      this.topZindex = (this.findTopZindex())+1;
      this.targetEl = this.defineTargetElement(this.settings.target);
      this.create();
  }
  create() {
      this.panel = new Panel({
          target: this.settings.input,
          attachToElement: this.settings.input,
          width: this.settings.width,
          height: this.settings.height,
          addClass: this.config.class
      }).create();
      this.panelEl = this.panel.instance;

      this.inputEl = document.querySelector(this.settings.input);
      this.inputEl.parentNode.appendChild(this.panelEl);

      // listener for auto-complete input
      this.inputEl.addEventListener('keyup', e => {
          this.targetEl.value = '';
          if(e.key === "Escape") {
              this.hide();
              this.inputEl.value = '';
          }
          else if(e.key === "ArrowUp") {
              let hoveringEl = this.panelEl.querySelector('li.autocomplete-list-item.hovering');
              if(hoveringEl && hoveringEl.previousSibling) {
                  this.panelEl.querySelectorAll('li.autocomplete-list-item.hovering').forEach(li => {
                      li.classList.remove('hovering');
                  });
                  hoveringEl = hoveringEl.previousSibling;
                  hoveringEl.classList.add('hovering');
              }
              else if(!hoveringEl) {
                  hoveringEl = this.panelEl.querySelector('li.autocomplete-list-item');
                  hoveringEl.classList.add('hovering');
              }
              this.checkScrollPosition(this.panelEl, hoveringEl);
          }
          else if(e.key === "ArrowDown") {
              let hoveringEl = this.panelEl.querySelector('li.autocomplete-list-item.hovering');
              if(hoveringEl && hoveringEl.nextSibling) {
                  this.panelEl.querySelectorAll('li.autocomplete-list-item.hovering').forEach(li => {
                      li.classList.remove('hovering');
                  });
                  hoveringEl = hoveringEl.nextSibling;
                  hoveringEl.classList.add('hovering');
              }
              else if(!hoveringEl) {
                  hoveringEl = this.panelEl.querySelector('li.autocomplete-list-item');
                  hoveringEl.classList.add('hovering');
              }
              this.checkScrollPosition(this.panelEl, hoveringEl);
          }
          else if(e.key === "Enter") {
              let hovering = this.panelEl.querySelector('li.autocomplete-list-item.hovering');
              if(hovering) {
                  this.doSelectItem({ id: hovering.dataset.id, display: hovering.innerHTML });
              }
          }
          else if(e.target.value.length >= this.settings.minimum) {
              this.doQuery(e.target.value);
          }
          else {
              this.hide();
          }
      });

      this.inputEl.addEventListener('blur', e => {
          this.blurTimer = setTimeout(() => {
              if(this.targetEl.value === '') {
                  this.hide();
                  this.reset();
              }
          },500);
      });

      return this;
  }
  destroy() {}
  show() {
      this.panel.show();
      this.createEventListeners();
      return this;
  }
  hide() {
      this.panel.hide();
      return this;
  }
  doQuery(query) {
      if(this.settings.url) {
          const url = this.settings.url.replace('__QUERY__',query); // console.log(url);
          try {
              fetch(url, { headers: { 'Accept': 'application/json' }})
              .then(res => res.json())
              .then(data => { this.buildList(this.settings.handleQueryData(data)) });
          } catch (err) {
              console.error('Tis broked!');
          }
      }
      else if(Array.isArray(this.settings.choices)) {
          this.buildList(this.settings.handleQueryData(this.settings.choices, query));
      }
  }
  buildList(data) { // console.log(data);
      if(data.length < 1) {
          this.hide();
      }
      else {
          let html = `<ul class="autocomplete-list">`;
          data.forEach(i => {
              html += `<li class='autocomplete-list-item' data-id='${i.id}'>${i.display}</li>`;
          });
          html += `</ul>`;
          this.panelEl.innerHTML = html;
          this.show();
      }
  }
  createEventListeners() {
      document.querySelectorAll('li.autocomplete-list-item').forEach(li => {
          li.addEventListener('click', e => {
              this.doSelectItem({ id: e.target.dataset.id, display: e.target.innerHTML });
          });
          li.addEventListener('mouseenter', e => {
              document.querySelectorAll('li.autocomplete-list-item.hovering').forEach(l => {
                  l.classList.remove('hovering');
              });
              li.classList.add('hovering');
          });
      });
  }
  doSelectItem(item) { // console.log(item)
      this.inputEl.value = item.display;
      this.targetEl.value = item.id;
      this.hide();
      this.settings.handleSelectItem(item);
  }
  checkScrollPosition(parentEl, childEl) {
      let parentPosition = parentEl.getBoundingClientRect();
      let childPosition = childEl.getBoundingClientRect();
      while(childPosition.top < parentPosition.top) {
          childPosition = childEl.getBoundingClientRect();
          parentEl.scrollTop = parentEl.scrollTop - 1;
      }
      while(childPosition.bottom > parentPosition.bottom) {
          childPosition = childEl.getBoundingClientRect();
          parentEl.scrollTop = parentEl.scrollTop + 1;
      }
  }
  reset() {
      this.inputEl.value = '';
      this.inputEl.blur();
      this.targetEl.value = '';
      this.settings.handleSelectItem(null);

      return this;
  }
  updateURL(url) {
      this.settings.url = url;
      this.hide();
      this.reset();

      return this;
  }
}

class Multiselect extends MyUI {
  config = {
      class: 'multiselect-ui'
  }
  settings = {
      input: null,
      target: null,
      width: null,
      height: null,
      url: '',
      parentSelectable: false,
      disabled: false,
      autoCreate: true,
      open: function() {},
      close: function() {},
      handleSelectedItems: function(selectedItemsArray) {},
      handleData: function(data) { return data; }
  }
  ArrowDown = '<i class="myui my-arrow-down"></i>';
  ArrowUp = '<i class="myui my-arrow-up"></i>';

  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      this.topZindex = (this.findTopZindex())+1;
      this.inputEl = document.querySelector(this.settings.input);
      this.targetEl = this.defineTargetElement(this.settings.target);
      this.create();
  }
  create() {
      this.panel = new Panel({
          target: this.settings.input,
          attachToElement: this.settings.input,
          width: this.settings.width,
          height: this.settings.height,
          addClass: this.config.class
      }).create();
      this.panelEl = this.panel.instance;

      let arrow = this.arrowInstance = document.createElement('span');
      arrow.classList.add('visibility-button');
      arrow.innerHTML = this.ArrowDown;
      this.arrowEl = this.inputEl.parentNode.insertBefore(arrow, this.inputEl.nextSibling);

      this.inputEl.parentNode.appendChild(this.panelEl);
      this.inputEl.innerHTML = this.ArrowDown;

      if(this.settings.isDisabled) {
          this.disable();
      } else {
          this.enable();
      }

      this.showSelected();
      if(this.settings.url) {
          this.doQuery();
      }
      else if(this.settings.choices) {
          this.storedOutput = this.buildList(this.settings.handleData(this.settings.choices));
      }

      this.inputEl.addEventListener('click', e => {
          this.toggleDisplay();
      });
      this.arrowEl.addEventListener('click', e => {
          this.toggleDisplay();
      });

      return this;
  }
  enable() {
      this.isDisabled = false;
      this.inputEl.classList.remove('multiselect-ui-disabled');
      return this;
  }
  disable() {
      this.isDisabled = true;
      this.inputEl.classList.add('multiselect-ui-disabled');
      return this;
  }
  toggleDisplay() {
      if(this.isDisabled) return false;
      if(this.panelEl.style.display === 'none') {
          // this.showSelectable();
          this.panel.show();
          this.arrowEl.innerHTML = this.ArrowUp;
          this.settings.open();
      }
      else {
          this.panel.hide();
          this.arrowEl.innerHTML = this.ArrowDown;
          this.currentlySelected = (this.targetEl.value) ? this.targetEl.value.split(',') : [];
          this.settings.handleSelectedItems(this.currentlySelected);
          this.settings.close();
      }
  }
  showSelected() {
      this.currentlySelected = (this.targetEl.value) ? this.targetEl.value.split(',') : []; // console.log(this.currentlySelected);
      this.inputEl.value = this.currentlySelected.length + ' Selected';
  }
  addItemListeners() {
      document.querySelectorAll('.multiselect-ui li input').forEach(input => {
          input.addEventListener('change', e => {
              let el = e.target;
              let label = el.parentElement;
              if(el.checked) {
                  el.parentElement.classList.add('selected');
                  this.currentlySelected.push(el.id);
              }
              else {
                  el.parentElement.classList.remove('selected');
                  this.currentlySelected = this.currentlySelected.filter(j => j !== el.id);
              }
              this.targetEl.value = this.currentlySelected.join(',');
              this.showSelected();
          })
      });
  }
  doQuery(newUrl) {
      if(newUrl) this.settings.url = newUrl;
      if(this.isDisabled) return false;
      const url = this.settings.url;
      try {
          fetch(url, { headers: { 'Accept': 'application/json' }})
          .then(res => res.json())
          .then(data => { this.storedOutput = this.buildList(this.settings.handleData(data)) });
      } catch (err) {
          console.error('Tis borked!');
      }
  }
  buildList(handledData) {
      const actuallySelected = [];
      let html = '<div class="selectable">';
      html += '<ul>';
      handledData.forEach(i => {
          let checked = (this.currentlySelected.includes(i.id)) ? 'checked' : '';
          let selected = (this.currentlySelected.includes(i.id)) ? 'selected' : '';
          if(this.currentlySelected.includes(i.id)) actuallySelected.push(i.id);
          if(i.children) {
              html += '<li class="parent">';
              if(this.parentSelectable) {
                  html += '<label class="'+ selected +'"><input type="checkbox" id="'+ i.id +'" '+ checked +' />'+ i.name +'</label>';
              }
              else {
                  html += i.name;
              }
              html += '<ul>';
              i.children.forEach(j => {
                  let checked = (this.currentlySelected.includes(j.id)) ? 'checked' : '';
                  let selected = (this.currentlySelected.includes(j.id)) ? 'selected' : '';
                  if(this.currentlySelected.includes(j.id)) actuallySelected.push(j.id);
                  html += '<li><label class="'+ selected +'"><input type="checkbox" id="'+ j.id +'" '+ checked +' />'+ j.name +'</label></li>';
              });
              html += '</ul>';
              html += '</li>';
          }
          else {
              html += '<li><label class="'+ selected +'"><input type="checkbox" id="'+ i.id +'" '+ checked +' />'+ i.name +'</label></li>';
          }
      });
      html += '</ul>';
      html += '</div>';

      this.targetEl.value = actuallySelected.join(',');
      this.showSelected();
      this.panelEl.innerHTML = html;
      this.addItemListeners();
  }
  updateURL(url) {
      this.settings.url = url;
      this.doQuery();
  }
}

class Calendar extends MyUI {
  config = {
      type: 'div',
      class: 'panel-ui',
      display: 'block'
  }
  settings = {
      input: null,
      target: null,
      width: null,
      height: null,
      noFuture: true,
      selectedDate: null,
      dateFormat: 'mm/dd/yyyy',
      autoCreate: true,
      handleSelectDate: function(dateObj) {}
  }
  months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  listening = false;

  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      // this.settings.overlay.target = this.settings.target;
      this.topZindex = (this.findTopZindex())+1;
      this.input = this.settings.input;
      this.targetEl = this.defineTargetElement(this.settings.target);
      if(this.settings.autoCreate) this.create();
  }
  create() {
      this.inputEl = document.querySelector(this.settings.input);

      let inputValue = this.inputEl.value;
      if(inputValue) {
          this.settings.selectedDate = inputValue;
      }

      this.display = {};
      this.selected = {};
      if(this.settings.selectedDate) {
          this.selected.year = this.display.year = new Date(this.settings.selectedDate).getFullYear();
          this.selected.month = this.display.month = new Date(this.settings.selectedDate).getMonth();
          this.selected.day = this.display.day = new Date(this.settings.selectedDate).getDate();
      }
      else {
          this.display.year = new Date().getFullYear();
          this.display.month = new Date().getMonth();
          this.display.day = new Date().getDate();
      }

      // create panel (to display selectable items)
      this.panel = new Panel({
          target: this.settings.input,
          attachToElement: this.settings.input,
          width: this.settings.width,
          height: this.settings.height,
          addClass: 'calendar-ui'
      }).create();
      this.panelEl = this.panel.instance;

      this.buildMonth();

      this.inputEl.parentNode.appendChild(this.panelEl);

      document.querySelector('body').addEventListener('click', e => {
          this.hide();
      });
      document.querySelectorAll('input, select, textarea').forEach(i => {
          i.addEventListener('focus', e => {
              this.hide();
          });
      });

      this.inputEl.addEventListener('click', e => {
          this.show();
          e.stopPropagation();
      });
      this.inputEl.addEventListener('focus', e => {
          this.show();
          e.stopPropagation();
      });

      this.inputEl.addEventListener('keyup', e => {
          if(e.key === "Escape") {
              this.hide();
          }
          if(e.key === "Enter") {
              this.show();
          }
      });

      return this;
  }
  toggleDisplay() {
      if(this.panelEl.style.display === 'none') {
          // this.showSelectable();
          this.panel.show();
          // this.arrowEl.innerHTML = this.ArrowUp;
      }
      else {
          this.panel.hide();
          // this.arrowEl.innerHTML = this.ArrowDown;
      }
  }
  show() {
      this.table.remove();

      let inputValue = this.inputEl.value;
      if(inputValue) {
          this.settings.selectedDate = inputValue;

          this.selected.year = this.display.year = new Date(this.settings.selectedDate).getFullYear();
          this.selected.month = this.display.month = new Date(this.settings.selectedDate).getMonth();
          this.selected.day = this.display.day = new Date(this.settings.selectedDate).getDate();
      }
      this.buildMonth();
      this.panel.show();
  }
  hide() {
      this.panel.hide();
  }
  formatOutput(dateObj) {
      let output = this.settings.dateFormat;

      let year = ''+dateObj.y; // convert to string
      year = (year.length === 2 ? '20' : '') + year; // add century if missing
      let month = ''+(parseInt(dateObj.m)+1); // convert to string
      month  = (month.length === 1 ? '0' : '') + month; // add 0 if single digit
      let day = ''+dateObj.d; // convert to string
      day = (day.length === 1 ? '0' : '') + day; // add 0 if single digit

      output = output.replace(/yyyy/, year);
      output = output.replace(/mm/, month);
      output = output.replace(/dd/, day);
      console.log(output);

      this.settings.selectedDate = output;

      this.selected.year = this.display.year = new Date(this.settings.selectedDate).getFullYear();
      this.selected.month = this.display.month = new Date(this.settings.selectedDate).getMonth();
      this.selected.day = this.display.day = new Date(this.settings.selectedDate).getDate();

      return output;
  }
  addEventListeners() {
      this.prev.addEventListener('click', e => {
          // this.inputEl.focus();
          this.goToPrevMonth();
          e.stopPropagation();
      });
      this.next.addEventListener('click', e => {
          // this.inputEl.focus();
          this.goToNextMonth();
          e.stopPropagation();
      });
      this.table.querySelectorAll('.is-day').forEach(d => {
          d.addEventListener('click', e => {
              this.doSelectItem(JSON.parse(e.target.getAttribute('data-date')));
              e.stopPropagation();
          });
      });
      this.table.addEventListener('click', e => {
          e.stopPropagation();
      });
  }
  doSelectItem(dateObj) { // console.log(item)
      this.inputEl.value = dateObj.formatted = this.formatOutput(dateObj);
      this.hide();
      this.settings.handleSelectDate(dateObj);
  }
  buildMonth() {
      const currentDate = new Date();
      const firstDayOfMonth = (y, m) => new Date(y, m, 1).getDay();
      const daysInMonth = (y, m) => new Date(y, m+1, 0).getDate();

      let table = this.table = document.createElement('table');
      let head = document.createElement('tr');
      let prev = this.prev = document.createElement('td');
      let month = document.createElement('td');
      let next = this.next = document.createElement('td');
      month.setAttribute('colspan', 5);
      month.classList.add('month-label');
      month.textContent = this.months[this.display.month]+' '+this.display.year;
      prev.textContent = "<";
      prev.classList.add('prev');
      next.textContent = ">";
      next.classList.add('next');
      head.appendChild(prev);
      head.appendChild(month);
      head.appendChild(next);
      table.appendChild(head);

      let d=0;
      for(let i=0; i<6; i++) {
          if(d < daysInMonth(this.display.year, this.display.month)) {
              let tr = document.createElement('tr');
              for(let j=0; j<7; j++) {
                  let td = document.createElement('td');
                  td.classList.add('day');
                  if((d > 0 && d < daysInMonth(this.display.year, this.display.month)) || (firstDayOfMonth(this.display.year, this.display.month) === j && d < daysInMonth(this.display.year, this.display.month))) {
                      d++;
                      td.textContent = d;
                      if((this.settings.noFuture && new Date(this.display.year, this.display.month, d).getTime() <= new Date().getTime()) || !this.settings.noFuture) {
                          td.classList.add('is-day');
                          td.setAttribute('data-date','{"y":"'+this.display.year+'","m":"'+this.display.month+'","d":"'+d+'"}');
                          if(d == currentDate.getDate() && this.display.month == currentDate.getMonth() && this.display.year == currentDate.getFullYear()) {
                              td.classList.add('today');
                          }
                          if(this.selected) {
                              if(d == this.selected.day && this.selected.month == this.display.month && this.selected.year == this.display.year) {
                                  td.classList.add('selected');
                              }
                          }
                      }
                  }
                  else {
                      td.classList.add('unavailable');
                  }
                  tr.appendChild(td);
              }
              table.appendChild(tr);
          }
      }

      this.panelEl.appendChild(table);
      this.addEventListeners();
  }
  goToNextMonth() {
      this.table.remove();
      if(this.display.month === 11) {
          this.display.month = 0;
          this.display.year++;
      }
      else {
          this.display.month++;
      }
      this.buildMonth();
  }
  goToPrevMonth() {
      this.table.remove();
      if(this.display.month === 0) {
          this.display.month = 11;
          this.display.year--;
      }
      else {
          this.display.month--;
      }
      this.buildMonth();
  }
}

class Clock extends MyUI {
  config = {
      type: 'div',
      class: 'panel-ui',
      display: 'block'
  }
  settings = {
      input: null,
      target: null,
      width: null,
      height: null,
      selectedTime: null,
      timeFormat: 'hh:MM AP',
      twentyFourHour: false,
      autoCreate: true,
      handleSelectTime: function(timeObj) {}
  }
  emptyInput = false;

  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      // this.settings.overlay.target = this.settings.target;
      this.topZindex = (this.findTopZindex())+1;
      this.input = this.settings.input;
      this.targetEl = this.defineTargetElement(this.settings.target);
      if(this.settings.autoCreate) this.create();
  }

  create() {
      this.inputEl = document.querySelector(this.settings.input);
      this.inputEl.setAttribute('readonly','readonly');

      let inputValue = this.inputEl.value;
      if(inputValue) {
          this.settings.selectedTime = inputValue;
      }
      else {
          this.emptyInput = true;
      }

      this.display = {};
      this.selected = {};
      if(this.settings.selectedTime) {
          this.selected.hour = this.display.hour = new Date('1/1/1970 '+this.settings.selectedTime).getHours();
          this.selected.minute = this.display.minute = new Date('1/1/1970 '+this.settings.selectedTime).getMinutes();
          this.selected.ampm = this.display.ampm = null;
      }
      else {
          this.selected.hour = this.display.hour = new Date().getHours();
          this.selected.minute = this.display.minute = new Date().getMinutes();
          this.selected.ampm = this.display.ampm = null;
      }

      if(!this.settings.twentyFourHour) {
          this.display.ampm = (this.selected.hour >= 12) ? 'PM' : 'AM';
          if(this.selected.hour === 0) {
              this.display.hour = 12;
          }
          else if(this.selected.hour > 12) {
              this.display.hour = this.display.hour - 12;
          }
      }

      this.panel = new Panel({
          target: this.settings.input,
          attachToElement: this.settings.input,
          width: this.settings.width,
          height: this.settings.height,
          addClass: 'clock-ui'
      }).create();
      this.panelEl = this.panel.instance;

      let clock = this.clock = document.createElement('div');

      let hourLabel = document.createTextNode('Hour:');
      let hour = this.hour = document.createElement('input');
      hour.setAttribute('name', 'ui-hour-slider');
      hour.setAttribute('class', 'ui-slider');
      hour.setAttribute('type', 'range');
      hour.setAttribute('min', 0);
      hour.setAttribute('max', 23);
      hour.setAttribute('value', this.selected.hour);
      hour.oninput = (e) => {
          this.selected.hour = e.target.value;
          this.formatOutput();
      }

      let minuteLabel = document.createTextNode('Minute:');
      let minute = this.minute = document.createElement('input');
      minute.setAttribute('name', 'ui-minute-slider');
      minute.setAttribute('class', 'ui-slider');
      minute.setAttribute('type', 'range');
      minute.setAttribute('min', 0);
      minute.setAttribute('max', 55);
      minute.setAttribute('step', 5);
      minute.setAttribute('value', this.selected.minute);
      minute.oninput = (e) => {
          this.selected.minute = e.target.value;
          this.formatOutput();
      }

      this.clock.appendChild(hourLabel);
      this.clock.appendChild(document.createElement('br'));
      this.clock.appendChild(hour);
      this.clock.appendChild(document.createElement('br'));
      this.clock.appendChild(document.createElement('br'));
      this.clock.appendChild(minuteLabel);
      this.clock.appendChild(document.createElement('br'));
      this.clock.appendChild(minute);

      this.panelEl.appendChild(clock);

      this.inputEl.parentNode.appendChild(this.panelEl);

      if(this.emptyInput) {
          this.formatOutput();
      }

      document.querySelector('body').addEventListener('click', e => {
          this.hide();
      });
      document.querySelectorAll('input, select, textarea').forEach(i => {
          i.addEventListener('focus', this.hide());
      });
      this.hour.removeEventListener('focus', this.hide());
      this.minute.removeEventListener('focus', this.hide());

      this.panelEl.addEventListener('click', e => {
          e.stopPropagation();
      });
      this.hour.addEventListener('click', e => {
          e.stopPropagation();
      });
      this.minute.addEventListener('click', e => {
          e.stopPropagation();
      });

      this.inputEl.addEventListener('click', e => {
          this.toggleDisplay();
          e.stopPropagation();
      });

      return this;
  }
  toggleDisplay() {
      if(this.panelEl.style.display === 'none') {
          this.panel.show();
      }
      else {
          this.panel.hide();
      }
  }
  show() {
      let inputValue = this.inputEl.value;
      if(inputValue) {
          this.settings.selectedTime = inputValue;

          this.selected.hour = this.display.hour = new Date('1/1/1970 '+this.settings.selectedTime).getHours();
          this.selected.minute = this.display.minute = new Date('1/1/1970 '+this.settings.selectedTime).getMinutes();
          this.selected.ampm = this.display.ampm = null;

          if(!this.settings.twentyFourHour) {
              this.display.ampm = (this.display.hour >= 12) ? 'PM' : 'AM';
              if(this.display.hour === 0) {
                  this.display.hour = 12;
              }
              else if(this.display.hour > 12) {
                  this.display.hour = this.display.hour - 12;
              }
          }
      }

      this.panel.show();
  }
  hide() {
      this.panel.hide();
  }
  formatOutput() {
      let output = this.settings.timeFormat;

      this.display.minute = this.selected.minute;
      if(!this.settings.twentyFourHour) {
          this.display.ampm = (this.selected.hour >= 12) ? 'PM' : 'AM';
          if(this.selected.hour == 0) {
              this.display.hour = 12;
          }
          else if(this.selected.hour > 12) {
              this.display.hour = this.selected.hour - 12;
          }
          else {
              this.display.hour = this.selected.hour;
          }
      }
      else {
          this.display.hour = this.selected.hour;
          this.display.ampm = null;
      }

      let hour = ''+this.display.hour;
      let HOUR = (hour.length < 2 && this.settings.twentyFourHour) ? '0'+hour : hour;
      let minute = ''+this.display.minute;
      let MINUTE = (minute.length < 2) ? '0'+minute : minute;
      let ampm = (this.settings.twentyFourHour) ? '' : this.display.ampm;

      output = output.replace(/hh/, hour);
      output = output.replace(/HH/, HOUR);
      output = output.replace(/mm/, minute);
      output = output.replace(/MM/, MINUTE);
      output = output.replace(/AP/, ampm);
      output = output.replace(/ap/, ampm.toLowerCase());

      this.inputEl.value = output;

      return output;
  }
  addEventListeners() {
  }
}

class Shout extends MyUI {
  config = {
      type: 'div',
      class: 'shout-ui',
      display: 'block'
  }
  settings = {
      text: 'Alert!',
      backgroundColor: '#2a67a8',
      fontColor: '#ffffff',
      duration: 3, // seconds --- duration * 1000
      closeOnClick: true,
      width: '50%',
      height: '50px',
      allBold: false,
      allCaps: false
  }
  constructor(settings) {
      super();
      for(let s in settings) {
          this.settings[s] = settings[s];
      }
      this.topZindex = (this.findTopZindex())+1;
      this.create();
  }
  create() {
      this.topZindex+=1;
      if(!document.querySelector('body .shout-container')) {
          this.parent = document.createElement(this.config.type);
          this.parent.classList.add('shout-container');
          document.querySelector('body').appendChild(this.parent);
      }
      else {
          this.parent = document.querySelector('body .shout-container');
      }
      this.parent.style.zIndex = this.topZindex;

      this.instance = document.createElement(this.config.type);
      this.instance.classList.add(this.config.class);
      this.instance.style.width = this.settings.width;
      this.instance.style.height = this.settings.height;
      this.instance.style.lineHeight = this.settings.height;
      this.instance.style.background = this.settings.backgroundColor;
      this.instance.style.color = this.settings.fontColor;
      this.instance.innerHTML = this.settings.text;
      this.parent.appendChild(this.instance);

      if(this.settings.allBold) {
          this.instance.style.fontWeight = 'bold';
      }
      if(this.settings.allCaps) {
          this.instance.style.textTransform = 'uppercase';
      }
      if(this.settings.closeOnClick) {
          this.instance.style.cursor = 'pointer';
          this.instance.addEventListener("click", () => { this.destroy(); });
      }
      if(this.settings.duration > 0) {
          setTimeout(() => {
              this.destroy();
          }, (this.settings.duration * 1000));
      }

      return this;
  }
  destroy() {
      this.instance.remove();
  }

}
