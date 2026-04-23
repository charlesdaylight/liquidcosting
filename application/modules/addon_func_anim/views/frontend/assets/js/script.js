if (typeof $uifm === 'undefined') { 
	$uifm = jQuery; 
} 
var zfaddn_anim_front = zfaddn_anim_front || null; 
if (!$uifm.isFunction(zfaddn_anim_front)) { 
	(function($, window) { 
		'use strict'; 
 
		var zfaddn_animFront = function() { 
			var zgfm_variable = []; 
			zgfm_variable.innerVars = {}; 
			zgfm_variable.externalVars = {}; 
			var _self = this; 
			this.initialize = function() { 
				_self.load_events(); 
			}; 
 
			this.load_events = function() { 
				  
				var tmp_obj_anim = $('.zfaddn_anim_prevtostart:not(.zfaddn_anim_start)'); 
 
				tmp_obj_anim.each(function(i) { 
					var tmp_obj_sel = $(this); 
					//run animation 
					void 0 !== $.fn.waypoint && 
						tmp_obj_sel.waypoint( 
							function() { 
								tmp_obj_sel.addClass('zfaddn_anim_start animated'); 
							}, 
							{ 
								offset: '85%', 
							} 
						); 
				}); 
			}; 
		}; 
		window.zfaddn_anim_front = zfaddn_anim_front = $.zfaddn_anim_front = new zfaddn_animFront(); 
		 
		//adding hook 
        const { addFilter } = wp.hooks; 
        //before submit form 
        addFilter('zgfmfront.initForm_loadAddLibs', 'zfaddn_anim_front/initialize', zfaddn_anim_front.initialize); 
         
	})($uifm, window); 
} 
 
$uifm(document).ready(function() {}); 
