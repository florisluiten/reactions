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

			scoreForm.onsubmit = function () {
				var select = this.querySelector('select');

				self.applyScoreFilter(this.closest('section'), select.options[select.selectedIndex].value * 1);

				return false;
			};

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

			el = document.createElement('input');
			el.setAttribute('type', 'submit');
			el.setAttribute('value', 'Pas filter toe');

			form.appendChild(el);

			return form;
		},

		/**
		 * For the container check each .wrapper and apply a filter to it
		 * when it is lower than the provided score
		 *
		 * @param {DOM}     container The container to look for .wrapper
		 * @param {integer} score     The score treshold
		 *
		 * @return {void}
		 */
		applyScoreFilter: function (container, score) {
			return;
		}
	};

	var displaySettingBox = document.getElementsByClassName('displaySettingBox');
	if (displaySettingBox.length > 0) {
		self.setupFilter(displaySettingBox[0]);
	}
}());
