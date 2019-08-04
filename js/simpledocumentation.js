(function($) {

  var tinyMCE = tinyMCE || window.tinyMCE,
    $list_admin,
    $editor,
    $input,
    $file,
    $list,
    $add,
    $switch_button,
    $import_button,
    $import_text,
    $export_button,
    $export_options,
    $export_text,
    $add_item,
    $item_content,
    $item_title,
    $item_type,
    $item_link,
    $item_file,
    $item_filename,
    $item_users,
    $item_id,
    $overlay,
    $upload_button,
    current_view = 'list',
    file_frame,
    vars = window.simple_documentation_vars || {};

  function init() {
    $list_admin = $('#simpledoc_list');
    $editor = $('#smpldoc_editor');
    $input = $('#smpldoc_input');
    $file = $('#smpldoc_file');
    $list = $('#sd_list');
    $add = $('#sd_add');
    $switch_button = $('#swtch_btn');
    $import_button = $('#sd_import_button');
    $import_text = $('#sd_import');
    $export_button = $('#sd_export_button');
    $export_options = $('#sd_export_options');
    $export_text = $('#sd_export');
    $item_content = $('#smpldoc_item_content');
    $item_title = $('#smpldoc_item_title');
    $item_type = $('#item_type');
    $item_link = $('#smpldoc_item_link');
    $item_file = $('#smpldoc_item_file');
    $item_filename = $('#smpldoc_filename');
    $item_users = $('.smpldoc_item_users');
    $item_id = $('#item_id');
    $overlay = $('#smpldoc_overlay');
    $upload_button = $('.cd_button_upload');
    $add_item = $('#smpldoc_additem');

    // setup listeners
    $list_admin.on('click', 'li', on_item_click);
    $switch_button.on('click', on_switch_click);
    $add.find('ul.add_list').on('click', 'a', on_type_option_click);
    $upload_button.on('click', on_upload_click);
    $add_item.on('click', on_add_item_submit);
    $list_admin.on('click', '.smpldoc_delete_item', on_delete_item);
    $list_admin.on('click', '.smpldoc_edit_item', on_edit_click);
    $export_button.on('click', on_export_click);
    $import_button.on('click', on_import_click);

    $list_admin.sortable({
      containment: 'parent',
      axis: 'y',
      handle: '.smpldoc_sort',
      opacity: .6,
      update: on_sort_update
    });
  }


  /**
   * @param {string} value
   * @return {string}
   */
  function stripslashes(value) {
    if (!value || value === '') {
      return value;
    }

    value = value.replace(/\\'/g,'\'');
    value = value.replace(/\\"/g,'"');
    value = value.replace(/\\/g,'');
    value = value.replace(/\\\\/g,'\\');
    value = value.replace(/\\0/g,'\0');

    return value;
  }


  /**
   * @param {string} type
   * @return {string}
   */
  function get_icon(type) {
    switch (type) {
      case 'video':
        return 'youtube-play';
      case 'link':
        return 'link';
      case 'file':
        return 'files-o';
      default:
        return 'comments';
    }
  }


  /**
   * @return {boolean|*}
   */
  function get_editor() {
    var _editor = tinyMCE || window.tinyMCE || window.tinymce;

    if (_editor) {
      return _editor.get('smpldoc_item_content');
    }

    return false;
  }


  function reset_form_view() {
    var editor = get_editor();

    if (editor) {
      editor.setContent('');
    } else {
      $item_content.val('');
    }

    $item_id.val('');
    $item_title.val('');
    $item_type.val('');
    $item_link.val('');
    $item_file.val('');
    $item_users.removeAttr('checked');
    $add.find('ul.add_list').find('li').removeClass('smpldoc_active smpldoc_disabled');
  }


  function set_list_view() {
    $add.fadeOut(function() {
      $list.fadeIn();
      current_view = 'list';
      $switch_button.html( vars.add_new );
    });

    reset_form_view();
  }


  function on_item_click() {
    var $this = $(this),
      delay = 0,
      activeClass = 'hover';

    // Close active
    $('.' + activeClass).each(function() {
      var $hover = $(this);

      if ($hover.attr('id') !== $this.attr('id')) {
        $hover.find('.el_expand').animate({
          height: 'toggle',
          paddingTop: 'toggle',
          paddingBottom: 'toggle'
        }, 300, function(){
          $hover.removeClass(activeClass);
        });
      }
    });

    $this.find('.el_expand').animate({
      height: 'toggle',
      paddingTop: 'toggle',
      paddingBottom: 'toggle'
    }, 700);

    setTimeout(function() {
      $this.toggleClass(activeClass);
    }, delay);
  }


  function on_switch_click(e) {
    e.preventDefault();

    if (current_view === 'list') {
      $list.fadeOut(function () {
        $add.fadeIn();
        current_view = 'add';
        $switch_button.html( vars.view_list );
      });
    } else {
      set_list_view();
    }
  }


  /**
   * @param {string} type
   * @param {object} [data]
   */
  function setup_fields_for_type(type, data) {
    var content = '',
      attachment_id = 0,
      attachment_url = '',
      attachment_filename = '',
      title = '',
      editor = get_editor(),
      fade_out_duration = 200,
      fade_in_duration = 100;

    $item_type.val(type);

    if ( data ) {
      title = data.title;
      content = data.content;
      attachment_id = data.attachment_id;
      attachment_url = data.attachment_url;
      attachment_filename = data.attachment_filename;

      if (data.ID || data.id) {
        $item_id.val( data.ID || data.id );
      }

      $('.add_list').find('li').addClass('smpldoc_disabled');
      $('#smdoc_' + type + '_cat').closest('li').removeClass('smpldoc_disabled').addClass('smpldoc_active');
      $('#smpldoc_additem').attr('data-action', 'edit').text(vars.save_changes);

      if (data.restricted) {
        $item_users.each(function() {
          var $role = $(this);

          if ( data.restricted.indexOf($role.val()) !== -1 ) {
            $role.attr('checked', '');
          }
        });
      }
    }

    if (title) {
      $item_title.val( title );
    }

    if ( type === 'note' || type === 'video' ) {
      $input.fadeOut(fade_out_duration);
      $file.fadeOut(fade_out_duration);

      if (editor) {
        editor.setContent(content);
      } else {
        $item_content.val(content);
      }

      $editor.delay(fade_out_duration).fadeIn(fade_in_duration);
    }

    if ( type === 'file' ) {
      $input.fadeOut(fade_out_duration);
      $editor.fadeOut(fade_out_duration);

      if (attachment_id) {
        $item_file.val(attachment_id);
      }

      $file.delay(fade_out_duration).fadeIn(fade_in_duration);

      if (attachment_url && attachment_filename) {
        $item_filename.empty().append($(generate_link_tag(attachment_url, attachment_filename)));
      }
    }

    if ( type === 'link' ) {
      $editor.fadeOut(fade_out_duration).val('');
      $file.fadeOut(fade_out_duration).val('');

      if (content) {
        $item_link.val(content);
      }

      $input.delay(fade_out_duration).fadeIn(fade_in_duration);
    }
  }


  function on_type_option_click(e) {
    e.preventDefault();

    var $this = $(this),
      type = $this.attr('data-type');

    $add.find('ul.add_list').find('li').removeClass('smpldoc_active').addClass('smpldoc_disabled');
    $this.parent('li').addClass('smpldoc_active');

    setup_fields_for_type(type);

    $overlay.fadeOut(300);
  }


  function on_upload_click(e) {
    e.preventDefault();

    var $button = $(this);

    if ( ! file_frame ) {
      file_frame = wp.media.frames.file_frame = wp.media({
        title: $button.data('uploader_title'),
        button: {
          text: $button.data('uploader_button_text')
        },
        multiple: false
      });

      file_frame.on('select', on_file_select);
    }

    file_frame.open();
  }


  function on_file_select() {
    var selected = file_frame.state().get('selection').first().toJSON();

    $item_file.attr('value', selected.id);
    $item_filename.text(selected.filename);
  }


  /**
   * @param {array} fields
   */
  function report_missing_fields(fields) {
    var message = vars.fields_missing + "\n";

    $.each(fields, function(index, field_name) {
      if (index > 0) {
        message += ', ';
      }

      message += field_name;
    });

    alert(message);
  }


  function fill_form(data) {
    if ( current_view !== 'list' ) {
      return;
    }

    setup_fields_for_type(data.type, data);

    $list.fadeOut(function() {
      $add.fadeIn();
      current_view = 'add';
      $switch_button.html( vars.view_list );
    });

    $overlay.fadeOut(300);
  }


  /**
   * @param {string} url
   * @param {string} label
   * @return {string}
   */
  function generate_link_tag(url, label) {
    return '<a href="' + url + '">' + label + '</a>';
  }


  /**
   * @param data
   * @return {jQuery}
   */
  function generate_list_item(data) {
    var icon_slug = get_icon(data.type),
      before = '<span class="el_front_bf"><a href="#" class="smpldoc_sort"><i class="fa fa-bars"></i></a> <i class="fa fa-' + icon_slug + '"></i></span>',
      after = [
        '<span class="el_front_af">',
        '<i class="fa fa-user smpldoc_usersallowed"></i> ', // white space required for consistent spacing
        '<a href="#edit" class="smpldoc_edit_item"><i class="fa fa-pencil"></i></a> ',
        '<a href="#delete" class="smpldoc_delete_item"><i class="fa fa-times"></i></a>',
        '</span>'
      ].join(''),
      title = '<span class="el_title">' + stripslashes(data.title) + '</span>',
      content,
      $expand = $('<div />').addClass('el_expand'),
      $front = $('<div />').addClass('el_front').attr('data-id', data.id),
      $element = $('<li />').attr('id', 'simpledoc_' + data.id);

    if (data.type === 'file') {
      content = generate_link_tag(data.attachment_url, data.attachment_url);
    } else if (data.type === 'link') {
      content = generate_link_tag(data.content, data.content);
    } else {
      content = data.content;
    }

    $expand.html(content);
    $front.append($(before)).append($(title)).append($(after));
    $element.append($front).append($expand);

    return $element;
  }


  function add_notification(message, classnames) {
    var $message = $('<div />');

    if (!classnames) {
      classnames = 'updated';
    }

    $message.addClass('smpldoc_notif');
    $message.addClass(classnames);
    $message.append(message);

    $('.wrap').prepend($message);

    setTimeout(function() {
      $message.fadeOut(700, function() {
        $message.remove();
      });
    }, 1500);
  }


  function handle_settings_response(_res) {
    var res = $.parseJSON(_res),
      $item,
      content;

    if (res.status === 'user-error') {
      if (res.type === 'empty_fields') {
        report_missing_fields(res.data);
      }
    }

    if (res.status === 'ok') {
      if (res.type === 'delete') {
        $('#simpledoc_' + res.id).fadeOut(500);
      }

      if (res.type === 'get-data') {
        fill_form(res.data);
      }

      if (res.type === 'edit') {
        $item = $('#simpledoc_' + res.data.id);
        $item.find('.el_title').html(res.data.title);

        if (res.data.type === 'link') {
          content = '<a href="' + res.data.content + '">' + res.data.content + '</a>';
        } else if (res.data.type === 'file') {
          content = '<a href="' + res.data.attachment_url + '">' + res.data.attachment_url + '</a>';
        } else {
          content = res.data.content || '';
        }

        $item.find('.el_expand').html(content);
        $item.find('.smpldoc_usersallowed').attr('title', res.data.users.join(', '));
        set_list_view();
      }

      if (res.type === 'add') {
        $list_admin.append(generate_list_item(res.data));
        set_list_view();
      }

      if (res.type === 'reorder') {
        add_notification($('<p />').html(vars.order_saved));
      }
    }
  }


  function on_add_item_submit(e) {
    e.preventDefault();

    var user_restriction = [],
      empty_fields = [],
      action_type = 'add',
      content = '',
      editor = get_editor(),
      item,
      data;

    $item_users.each(function() {
      var $this = $(this);

      if ($this.is(':checked')) {
        user_restriction.push($this.val());
      }
    });

    if (editor) {
      content = editor.getContent();
    } else {
      content = $item_content.val();
    }

    item = {
      title: $item_title.val(),
      type: $item_type.val(),
      input: $item_link.val(),
      file: $item_file.val(),
      editor: content,
      user_roles: user_restriction
    };

    if (!item.type || item.type === 'nope') {
      empty_fields.push('type');
    }

    if (item.title.length < 1) {
      empty_fields.push('title');
    }

    if ((item.type === 'note' || item.type === 'video') &&
      (!item.editor || item.editor.length < 1)) {
      empty_fields.push('content');
    }

    if (item.type === 'link' && item.input.length < 1) {
      empty_fields.push('link');
    }

    if (item.type === 'file' && (!item.file || item.file === 'nope')) {
      empty_fields.push('file');
    }

    if (empty_fields.length) {
      report_missing_fields(empty_fields);
      return false;
    }

    if ( $(this).attr('data-action') === 'edit' ) {
      action_type = 'edit';
      item.id = $item_id.val();
    }

    data = {
      action: 'simpleDocumentation_ajax',
      a: action_type,
      item: item
    };

    $.post(vars.ajax_url, data).done(handle_settings_response);
  }


  function on_delete_item(e) {
    e.preventDefault();

    var id = $(this).parent().parent().attr('data-id'),
      data = {
        action: 'simpleDocumentation_ajax',
        a: 'delete',
        id: id
      };

    $.post(vars.ajax_url, data).done(handle_settings_response);
  }


  function on_sort_update() {
    var ordering = [],
      data;

    $list_admin.find('li').each(function() {
      ordering.push($(this).find('.el_front').attr('data-id'));
    });

    data = {
      action: 'simpleDocumentation_ajax',
      a: 'reorder',
      data: ordering
    };

    $.post(vars.ajax_url, data).done(handle_settings_response);
  }


  function on_edit_click(e) {
    e.preventDefault();

    var id = $(this).parent().parent().attr('data-id'),
      data = {
        action: 'simpleDocumentation_ajax',
        a: 'get-data',
        id: id
      };

    $.post(vars.ajax_url, data).done(handle_settings_response);
  }


  function on_export_click(e) {
    e.preventDefault();

    var data = {
      action: 'simpleDocumentation_ajax',
      a: 'export',
      options: $export_options.is(':checked') ? 'include' : 'exclude'
    };

    $export_text.val( vars.loading + '...' );

    $.post(vars.ajax_url, data).done(function(response) {
      $export_text.val(response).removeAttr('disabled').removeClass('disabled');
    });
  }


  function on_import_click(e) {
    e.preventDefault();

    var data = {
      action: 'simpleDocumentation_ajax',
      a: 'import',
      data: $.parseJSON( $import_text.val() )
    };

    $import_text.val(vars.loading + '...');

    $.post(vars.ajax_url, data).done(function(_res) {
      var res = $.parseJSON(_res);

      if (res.status === 'ok') {
        $import_text.val(vars.label_done);
      } else {
        $import_text.val(vars.error);
      }
    });
  }

  $(document).ready(init);

})(jQuery);
