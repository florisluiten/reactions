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
		 * @var The minimum threshold for scores
		 */
		scoreMinThreshold: -1,

		/**
		 * @var The maximum threshold for scores
		 */
		scoreMaxThreshold: 3,

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
				el = null;

			for (var i = self.scoreMinThreshold; i <= self.scoreMaxThreshold; i++) {
				el = document.createElement('option');
				el.setAttribute('value', i);
				el.text = i;

				select.appendChild(el);
			}

			form.appendChild(select);

			return form;
		}
	};

	var displaySettingBox = document.getElementsByClassName('displaySettingBox');
	if (displaySettingBox.length > 0) {
		self.setupFilter(displaySettingBox[0]);
	}
}());
