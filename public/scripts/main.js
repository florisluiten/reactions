/**
 * Reactions
 *
 * @author	Floris Luiten <floris@florisluiten.nl>
 *
 * @return {void}
 */
(function () {
	'use strict';

	var self = {
		/**
		 * Setup filtering reactions based on the score
		 *
		 * @param {DOM} displaySettingBox The displaySettingBox element
		 *
		 * @return {void}
		 */
		setupFilter: function (displaySettingBox) {
			var scoreForm = self.buildScoreForm();

			displaySettingBox.appendChild(scoreForm);
		},

		/**
		 * Build a score form
		 *
		 * @return {DOM} The form
		 */
		buildScoreForm: function () {
			var form = document.createElement('form'),
				select = document.createElement('select'),
				option = document.createElement('option');

			option.setAttribute('value', '-1');
			option.text = '-1';
			select.appendChild(option);

			form.appendChild(select);

			return form;
		}
	};

	var displaySettingBox = document.getElementsByClassName('displaySettingBox');
	if (displaySettingBox.length > 0) {
		self.setupFilter(displaySettingBox[0]);
	}
}());
