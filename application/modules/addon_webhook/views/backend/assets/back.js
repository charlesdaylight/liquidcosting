if (typeof $uifm === 'undefined') {
	$uifm = jQuery;
}
var zgfm_back_addon_webhook = zgfm_back_addon_webhook || null;
if (!$uifm.isFunction(zgfm_back_addon_webhook)) {
	(function($, window) {
		'use strict';

		var zgfm_fn_webhook = function() {
			var variable = [];
			variable.innerVars = {};
			variable.externalVars = {};

			var _this = this;

			var defaults = {
				data: {
					status: '0',
					url: '',
					type: '',
					fields: [],
					customs: [],
					log: '0',
				},
			};

			var settings = $.extend(true, {}, defaults);

			this.initialize = function() {};

			this.dump_data = function() {
				console.log(_this.dumpvar3(settings));
			};

			this.refresh_options = function() {
				//show options
				_this.show_options();

				//load events
				_this.load_events();
			};

			this.load_settings = function() { 
				var idform; 
				if (rocketform.isMultiStepActive()) { 
					idform = $('#uifm_frm_mm_main_id').val(); 
				} else {  
					idform = $('#uifm_frm_main_id').val(); 
				} 
				$.ajax({ 
					type: 'POST', 
					url: rockfm_vars.uifm_siteurl + 'addon_webhook/zfad_webhook_back/ajax_load_settings', 
					data: { 
						action: 'zgfm_back_webhook_load_settings', 
						page: 'zgfm_form_builder', 
						zgfm_security: uiform_vars.ajax_nonce, 
						form_id: parseInt(idform), 
						csrf_field_name: uiform_vars.csrf_field_name, 
					}, 
					success: function(msg) { 
						//load data 
						if (msg.data.status) { 
							settings = $.extend(true, {}, defaults, { data: msg.data }); 
						} else { 
							settings = $.extend(true, {}, defaults); 
						} 
 
						//show options 
						zgfm_back_addon_webhook.show_options(); 
 
						//load events 
						zgfm_back_addon_webhook.load_events(); 
					}, 
				}); 
			}; 

			this.load_events = function() {
				$('.webhook-input').on('change focus', function(e) {
					if (e) {
						e.stopPropagation();
						e.preventDefault();
					}

					var f_store = $(e.target).data('options');
					var f_val = $(e.target).val();

					zgfm_back_addon_webhook.update_settings(f_store, f_val);
				});
			};
			
			this.settings_show_logs = function() {
				var id = $('#uifm_frm_main_id').val();
   
				//process fonts for fields
				 
				$('#zgpb-modal1')
							.find('.sfdc-modal-dialog').css('width','1000px');
						$('#zgpb-modal1')
							.find('.sfdc-modal-dialog')
							.find('.zgpb-modal-header-inner')
							.html('');
						$('#zgpb-modal1')
							.find('.sfdc-modal-dialog')
							.find('.sfdc-modal-body')
							.html('<div class="zgpb-loader-header-1"></div>');
						$('#zgpb-modal1')
							.find('.sfdc-modal-dialog')
							.find('.zgpb-modal-footer-wrap')
							.html('');
						$('#zgpb-modal1').sfdc_modal({
								show: true,
								keyboard: true,
							});	 
				 
				 
				$.ajax({
					type: 'POST',
					url: rockfm_vars.uifm_siteurl + 'addon_webhook/zfad_webhook_back/ajaxShowLogs',
					data: {
						action: 'zgfm_back_webhook_showlogs',
						page: 'zgfm_form_builder',
						csrf_field_name: uiform_vars.csrf_field_name,
						form_id: id,
					},
					success: function(msg) {
						$('#zgpb-modal1')
							.find('.sfdc-modal-dialog').css('width','1000px');
						$('#zgpb-modal1')
							.find('.sfdc-modal-dialog')
							.find('.zgpb-modal-header-inner')
							.html(msg.modal_header);
						$('#zgpb-modal1')
							.find('.sfdc-modal-dialog')
							.find('.sfdc-modal-body')
							.html(msg.modal_body);
						$('#zgpb-modal1')
							.find('.sfdc-modal-dialog')
							.find('.zgpb-modal-footer-wrap')
							.html(msg.modal_footer);
 
					},
				});
			};
			
			
			this.update_settings = function(f_store, value) {
				var opt1, opt2, opt3, tmp_store;
				var f_id = $('#uifm-field-selected-id').val();
				var f_step = $('#' + f_id)
					.closest('.uiform-step-pane')
					.data('uifm-step');

				tmp_store = f_store.split('-');
				opt1 = tmp_store[0];
				opt2 = tmp_store[1];
				opt3 = tmp_store[2];

				switch (String(opt1)) {
					case 'status':
						settings['data']['status'] = value;
						break;
					case 'url':
						settings['data']['url'] = value;
						break;
					case 'type':
						settings['data']['type'] = value;
						break;
					case 'format':
						settings['data']['format'] = value;
						break;
					case 'fields':
						settings['data']['fields'][opt2][opt3] = value;
						break;
					case 'customs':
						settings['data']['customs'][opt2][opt3] = value;
						break;
					case 'log':
						settings['data']['log'] = value;
				}
			};

			this.show_options = function() {
				//load settings on tab

				let tmp_status = settings['data']['status'];

				if (parseInt(tmp_status) === 1) {
					$('#webhook_status_2').prop('checked', true);
				} else {
					$('#webhook_status_1').prop('checked', true);
				}
					
				let tmp_log = settings['data']['log'];

				if (parseInt(tmp_log) === 1) {
					$('#webhook_log_2').prop('checked', true);
				} else {
					$('#webhook_log_1').prop('checked', true);
				}	
				
				let tmp_url = settings['data']['url'];
				$('#webhook_url').val(tmp_url);

				let tmp_type = settings['data']['type'];
				$('#webhook_type').val(tmp_type);
				
				let tmp_format = settings['data']['format'];
				$('#webhook_format').val(tmp_format);

				//load fields
				_this.settings_loadfields();

				//load customs
				_this.settings_loadCustomfields();
			};

			/*
			 * load custom fields
			 * @returns {undefined}
			 */
			this.settings_loadCustomfields = function() {
				$('#zgfm_webhook_back_form_cfields').html('');

				//load all fields

				var tmp_tmpl;
				var optindex;
				var numorder;
				let tmp_fields = settings['data']['customs'];
				//fill select list of form values
				$.each(tmp_fields, function(key, value) {
					tmp_tmpl = wp.template('zgfm-webhook-template2');

					optindex = value['id'];
					numorder = value['number'];

					$('#zgfm_webhook_back_form_cfields').append(
						tmp_tmpl({
							id: optindex,
							number: numorder,
							name: value['name'],
							cvalue: value['value'],
						})
					);
				});
			};
			
			var findIndexById = function (arr, targetId) { 
				for (let i = 0; i < arr.length; i++) {
					if (arr[i].number === parseInt(targetId)) {
					  return i;
					}
				  }
				  
				  // Return -1 if the item with the specified id is not found
				  return -1;
			}
			
			
			/*
			 * delete field
			 */
			this.delete_field = function(el) {
				/*show loader window*/
				rocketform.loading_panelbox2(1);

				var elem = $(el);

				var tmp_number = elem.closest('.form-row').attr('data-number');
				//delete data
				// delete settings['data']['fields'][tmp_number];
				var arrIndex = findIndexById(settings['data']['fields'], tmp_number);
				
				if (parseFloat(arrIndex) == -1) { 
					return;
				}
				
				settings['data']['fields'].splice(arrIndex, 1);
				
				//load fields
				_this.settings_loadfields();
				//hide panel
				rocketform.loading_panelbox2(0);
			};

			/*
			 * delete custom
			 */
			this.delete_custom = function(el) {
				/*show loader window*/
				rocketform.loading_panelbox2(1);

				var elem = $(el);

				var tmp_number = elem.closest('.form-row').attr('data-number');
				 
				var arrIndex = findIndexById(settings['data']['customs'], tmp_number);
				
				if (parseFloat(arrIndex) == -1) { 
					return;
				}
				
				settings['data']['customs'].splice(arrIndex, 1);
				 
				//load fields
				_this.settings_loadCustomfields();
				//hide panel
				rocketform.loading_panelbox2(0);
			};

			this.get_currentDataToSave = function(result) { 
				result['webhook'] = settings['data']; 
				return result; 
			}; 

			/*
			 * generate all fields
			 */
			this.settings_loadfields = function() {
				$('#zgfm_webhook_back_form_fields').html('');

				//load all fields
				var tmp_options = _this.dataFields_load();

				var tmp_tmpl;
				var optindex;
				var numorder;
				let tmp_fields = settings['data']['fields'];
				//fill select list of form values
				$.each(tmp_fields, function(key, value) {
					tmp_tmpl = wp.template('zgfm-webhook-template1');

					optindex = value['id'];
					numorder = value['number'];

					$('#zgfm_webhook_back_form_fields').append(
						tmp_tmpl({
							id: optindex,
							number: numorder,
							name: value['name'],
							field: value['field'],
						})
					);

					//fill select list of form values
					$.each(tmp_options, function(key2, value2) {
						$('#zgfm_webhook_back_form_fields div[data-uniqueid="' + optindex + '"]')
							.find('.webhook-field-value')
							.append(
								$('<option></option>')
									.attr('value', value2['id'])
									.attr('data-type', value2['type'])
									.text(value2['name'])
							);
					});

					$('#zgfm_webhook_back_form_fields div[data-uniqueid="' + optindex + '"]')
						.find('.webhook-field-value')
						.val(value['field']);
				});
			};

			/*
			 * Add new field
			 * @returns {undefined}
			 */
			this.settings_field_new = function() {
				//load all fields
				var tmp_options = _this.dataFields_load();

				var tmp_tmpl = wp.template('zgfm-webhook-template1');

				var lenArrs = $('#zgfm_webhook_back_form_fields').find('.form-row').length;
				var optindex;
				var numorder = 0;
				optindex = zgfm_back_helper.generateUniqueID(5);
				if (parseInt(lenArrs) === 0) {
					numorder = 0;
				} else {
					numorder = parseInt(lenArrs);
				}

				$('#zgfm_webhook_back_form_fields').append(
					tmp_tmpl({
						id: optindex,
						number: numorder,
						name: 'variable' + numorder,
						field: '',
					})
				);

				//fill select list of form values
				$.each(tmp_options, function(key, value) {
					$('#zgfm_webhook_back_form_fields div[data-uniqueid="' + optindex + '"]')
						.find('.webhook-field-value')
						.append(
							$('<option></option>')
								.attr('value', value['id'])
								.attr('data-type', value['type'])
								.text(value['name'])
						);
				});

				//store data
				settings['data']['fields'][numorder] = {};
				settings['data']['fields'][numorder] = {
					id: optindex,
					number: numorder,
					name: 'variable' + numorder,
					field: '',
					type: '',
				};

				//load events
				_this.load_events();
			};

			/*
			 * execute action after creating field
			 */
			this.onFieldCreation_post = function() {
				//load fields
				_this.settings_loadfields();
			};

			/*
			 * Update field when change
			 */
			this.settings_updateField = function(elem) {
				var el = $(elem);
				var tmp_id = el.closest('.form-row').attr('data-uniqueid');
				var tmp_number = el.closest('.form-row').attr('data-number');
				var value = el.find('option:selected').val();
				var tmp_type = el.find('option:selected').attr('data-type');

				//update data
				settings['data']['fields'][tmp_number]['field'] = value;
				settings['data']['fields'][tmp_number]['type'] = tmp_type;
			};

			this.settings_custom_new = function() {
				var tmp_tmpl = wp.template('zgfm-webhook-template2');
				var lenArrs = $('#zgfm_webhook_back_form_cfields').find('.form-row').length;
				var optindex;
				var optindex;
				var numorder = 0;
				optindex = zgfm_back_helper.generateUniqueID(5);
				if (parseInt(lenArrs) === 0) {
					numorder = 0;
				} else {
					numorder = parseInt(lenArrs);
				}
				$('#zgfm_webhook_back_form_cfields').append(
					tmp_tmpl({
						id: optindex,
						number: numorder,
						name: 'variable' + numorder,
						value: 'custom ' + numorder,
					})
				);

				//store data
				settings['data']['customs'][numorder] = {};
				settings['data']['customs'][numorder] = {
					id: optindex,
					number: numorder,
					name: 'variable' + numorder,
					value: 'custom ' + numorder,
				};

				//load events
				_this.load_events();
			};

			this.dev_show_vars = function() {
				console.log(_this.dumpvar3(settings));
			};

			this.setExternalVars = function() {};
			this.getExternalVars = function(name) {
				if (variable.externalVars[name]) {
					return variable.externalVars[name];
				} else {
					return '';
				}
			};
			this.setInnerVariable = function(name, value) {
				variable.innerVars[name] = value;
			};

			this.getInnerVariable = function(name) {
				if (variable.innerVars[name]) {
					return variable.innerVars[name];
				} else {
					return '';
				}
			};

			this.dumpvar3 = function(object) {
				return JSON.stringify(object, null, 2);
			};
			this.dumpvar2 = function(object) {
				return JSON.stringify(object);
			};

			this.dumpvar = function(object) {
				var seen = [];
				var json = JSON.stringify(object, function(key, val) {
					if (val != null && typeof val == 'object') {
						if (seen.indexOf(val) >= 0) return;
						seen.push(val);
					}
					return val;
				});
				return seen;
			};

			this.dataFields_load = function() {
				var tmp_fields = rocketform.get_coreData();
				var tmp_options = [];
				var tmp_inneropts = {};
				if (
					parseInt(
						$.map(tmp_fields['steps_src'], function(n, i) {
							return i;
						}).length
					) != 0
				) {
					$.each(tmp_fields['steps_src'], function(index3, value3) {
						$.each(value3, function(index4, value4) {
							if (parseInt($('#' + index4).length) != 0) {
								switch (parseInt(value4['type'])) {
									case 6:
									case 7:
									case 8:
									case 9:
									case 10:
									case 11:
									case 12:
									case 13:
									case 15:
									case 16:
									case 17:
									case 18:
									case 21:
									case 22:
									case 23:
									case 24:
									case 25:
									case 26:
									case 28:
									case 29:
									case 30:
									case 40:
									case 41:
									case 42:
									case 43:
										tmp_inneropts = {};
										tmp_inneropts['id'] = value4['id'];
										tmp_inneropts['name'] = value4['field_name'];
										tmp_inneropts['type'] = value4['type'];
										tmp_options.push(tmp_inneropts);
										break;
								}
							}
						});
					});
				}

				return tmp_options;
			};
		};
		window.zgfm_back_addon_webhook = zgfm_back_addon_webhook = $.zgfm_back_addon_webhook = new zgfm_fn_webhook();
//adding hook 
const { addFilter, addAction } = wp.hooks; 
//before submit form 
addAction('zgfm.onLoadForm_loadAddon', 'zgfm_back_addon_webhook/load_settings', zgfm_back_addon_webhook.load_settings); 
addFilter('zgfm.fieldName_onBlur', 'zgfm_back_addon_webhook/refresh_options', zgfm_back_addon_webhook.refresh_options); 
addFilter('zgfm.onFieldCreation_post', 'zgfm_back_addon_webhook/onFieldCreation_post', zgfm_back_addon_webhook.onFieldCreation_post); 
addFilter('zgfm.getData_beforeSubmitForm', 'zgfm_back_addon_webhook/get_currentDataToSave', zgfm_back_addon_webhook.get_currentDataToSave); 
	})($uifm, window);
}
