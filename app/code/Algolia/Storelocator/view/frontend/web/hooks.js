/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */

/**
 * Documentation: https://community.algolia.com/magento/doc/m2/frontend-events/
 **/

/**
 * Autocomplete hook method
 * autocomplete.js documentation: https://github.com/algolia/autocomplete.js
 **/
requirejs([
    'jquery',
    'algoliaBundle',
], function ($,algoliaBundle) {
		algolia.registerHook('beforeAutocompleteSources', function(sources, algoliaClient) {
			console.log('In hook method to modify autocomplete data sources');
			
			// Initialize the newly created index
		const customIndex = algoliaClient.initIndex("magento2_localdefault_storeslocators");
		const customIndexOptions = {
			hitsPerPage: 4
		};

		// id_of_your_template should be the value of the ID attribute in the <script> tag of your template
		const customTemplate = $('#autocomplete_storelocator_template').html();

		// Append the new source to the sources array
		sources.push({
			source: algoliaBundle.autocomplete.sources.hits(customIndex, customIndexOptions),
			templates: {
			suggestion(hit) {
				return algoliaBundle.Hogan.compile(customTemplate).render(hit);
			}
			}
		});
		//console.log(sources);
		return sources;
			
			//return sources;
		});

		
});