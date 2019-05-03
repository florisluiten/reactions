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
			var scoreForm = self.buildScoreForm(),
				container = document.createElement('div');

			scoreForm.onsubmit = function () {
				var select = this.querySelector('select');

				self.applyScoreFilter(this.closest('section'), select.options[select.selectedIndex].value * 1);

				return false;
			};

			container.classList.add('col-md-2');
			container.appendChild(scoreForm);

			displaySettingBox.appendChild(container);
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

			select.classList.add('form-control');

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
			el.classList.add('btn', 'btn-default');

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
			Array.from(container.querySelectorAll('.wrapper[data-score]')).forEach(function (element) {
				var thisScore = element.getAttribute('data-score') * 1;

				if (thisScore < score) {
					element.classList.add('hidden');
				} else {
					element.classList.remove('hidden');
				}
			});
		}
	};

	var displaySettingBox = document.getElementsByClassName('displaySettingBox');
	if (displaySettingBox.length > 0) {
		self.setupFilter(displaySettingBox[0]);
	}
}());
