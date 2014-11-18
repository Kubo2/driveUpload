
/**
 * This file is designed to be a part of Kunštár's WEB-UI for his students.
 */
/**
 * This file contains JavaScript integration with Google Drive Picker API
 * (for more information please refer to https://developers.google.com/picker/).
 *
 * @author Kubo2 <kelerest123@gmail.com>
 *
 */

/**
 * @namespace
 */
!window.driveUpload && (
	window.driveUpload = (function(w,u,t){
		// formally
		"don't use strict";

		// developer key and application client id from Google Developers Console
		var devKey, clientId;
		// the scope user is choosing file from
		var scope;

		var oauthToken;
		var pickerDialog;

		/**
		 * On API load callback name. (This callback function later assigned to this[apiLoadCallback] and thus way used.)
		 * Intentionally wasn't used Math.random() for this case of thing becuase of causing object inconsistencies.
		 *
		 * @type {string}
		 * @readonly
		 * @private
		 */
		 var apiLoadCallback = '_gapi_loaded_callback_' + (new Date()).getTime().toString();

		/**
		 * Whether the gapi was loaded.
		 *
		 * @default
		 * @readonly
		 * @public
		 */
		var apiLoaded = false;

		/**
		 * Initializes static window.driveUpload object.
		 *
		 * @param {string} d Developer Key obtained from Google Developers Console.
		 * @param {string} c Client ID obtained from Google Developers Console.
		 * @param {Array} s Scope of URLs 
		 * @returns {undefined}
		 * @throws {Error} If the static object is already initialized.
		 * @fires driveUpload#onApiLoad When the gapi is successfully loaded.
		 * @access public
		 */
		var init = function(d,c,s) {
			if(apiLoaded) throw new Error("driveUpload object initialized already");
			devKey = d;
			clientId = c;
			scope = s;

			// load API base script from Google
			var api;
			api = t.createElement('script');
			t.getElementsByTagName('head')[0].appendChild(api);
			api.src = "https://apis.google.com/js/api.js?onload=" + 'driveUpload.' + apiLoadCallback // this should be highlighted here
		}

		/**
		 * Is fired when the gapi object is successfully loaded, otherwise not.
		 *
		 * @event driveUpload#onApiLoad
		 * @public (but only symbolically public)
		 */
		this[apiLoadCallback] = function onApiLoad() {
			if(apiLoaded) return;
			gapi.load('auth', {'callback': _gapiAuthorize});
			gapi.load('picker', {
				callback: function() {
					apiLoaded = true
				}
			});
		}

		function _gapiAuthorize() {
			w.gapi.auth.authorize({
				'client_id': 		clientId,
				'scope': 		scope,
				'immediate': 	false
			}, function(result) {
				// handle auth result
				// result object not passed or result error occured
				if(!result || result['error']) {

					w.setTimeout(w.location.reload, 3600);
					return;
				}

				oauthToken = result.access_token;
			});
		}

		var allowedMedia = '', oldAMedia;
		/**
		 * Creates and shows new/stored Google Picker Dialog.
		 *
		 * @param {function} success If user has selected ("picked") some files, success parameter is invoked with those files passed.
		 * @param {function} falure The callback which is invoked in case user does not select any file or cancels the dialog.
		 * @public
		 */
		function createPicker(success, failure) {
			if(apiLoaded && oauthToken) {
				if(pickerDialog) {
					if(!pickerDialog.isVisible())
						pickerDialog.setVisible(true);
					if(oldAMedia === allowedMedia)
						return;
				}

				var pickerBuilder = new google.picker.PickerBuilder();
				var view = new google.picker.View(google.picker.ViewId.DOCS);
				
				if(allowedMedia.length) {
					oldAMedia = allowedMedia;
					view.setMimeTypes(allowedMedia);
				}

				var picker = pickerBuilder.
					addView(new google.picker.DocsUploadView()).
					addView(view).
					setOAuthToken(oauthToken).
					setDeveloperKey(devKey).
					setCallback(function(data) {
						if(data[google.picker.Response.ACTION] === google.picker.Action.PICKED) {
							success(data[google.picker.Response.DOCUMENTS]);
						} else {
							failure();
						}
					}).
				build();

				picker.setVisible(true);
				pickerDialog = picker;
			}
		}

		// this is very very ugly solution, I think, but Google APIs do not allow anything else but global functions
		w["driveUpload." + apiLoadCallback] = this[apiLoadCallback];

		/**
		 * Public interface of static driveUpload object.
		 *
		 * @namespace driveUpload
		 */
		return {
			/* properties */
			apiLoaded: apiLoaded,

			/* methods */
			init: init,
			showDialog: createPicker,

			/**
			 * Closes picker dialog.
			 *
			 * @public
			 */
			closeDialog: function() {
				if(pickerDialog && pickerDialog.isVisible())
					pickerDialog.setVisible(false);
			},

			/**
			 * Sets internet media types allowed for the dialog.
			 *
			 * @param {Array} mimes An array-list of mime types
			 */
			setMimes: function(mimes) {
				if(!pickerDialog || !pickerDialog.isVisible()) {
					if(mimes.length)
						allowedMedia = mimes.join(',');
				}
			},

			/**
			 * Shows internet media types allowed for the dialog.
			 *
			 * @returns {Array} An array-list of mime types
			 */
			getMimes: function() {
				return allowedMedia.split(',');
			}
		};
     })(window, undefined, document)
);