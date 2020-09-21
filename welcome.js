const blogContainer = document.querySelector( '.blog-posts' );
const tvContainer = document.querySelector( '.tv-shows' );

function fetchPosts( domain = 'https://jeremy.hu', postType = 'posts', number = 5 ) {
	const endpoint = `${domain}/wp-json/wp/v2/${postType}`;
	let output = [];

	fetch(endpoint)
		.then(blob => blob.json())
		// Let's build a better array, with just the info we need.
		.then((data) => {
			return data
				.map(post => {
					return {
						id: post.id,
						title: post.title.rendered,
						link: post.link,
						date: post.date,
						excerpt: post.excerpt.rendered,
						colors: post.colors,
						postType,
					};
				})
				.slice(0, number);
		})
		// Let's build our list using the info from our array.
		.then((data) => {
			let list = [
				`<ul class="display-${postType}">`,
				'</ul>',
			];

			// Create the links for each list.
			const links = data.map(post => {
				// Format the date.
				const date = new Date(post.date);
				const localDate = date.toLocaleDateString('en-US', {
					day : 'numeric',
					month : 'long',
					year : 'numeric',
				});

				// Add some colors.
				const style = `${post.colors ? `style="background-color:#${post.colors.color};color:rgb(${post.colors.contrast});"` : ''}`;

				// Return the built list item.
				return `
					<li class="post-${post.id}" ${style}>
						<a href="${post.link}" class="post-${post.id}">${post.title}</a> on <time datetime="${post.date}">${localDate}</time>
					</li>
				`;
			});

			// Wrap the links into our unordered list.
			return [...list.slice(0, 1), ...links, ...list.slice(1)];
		})
		// Add our output to the output array.
		.then(data => output.push(...data));

	return output;
}
const blogPosts = fetchPosts( 'https://jeremy.hu', 'posts', 10 );
const shows = fetchPosts( 'https://jeremy.hu', 'traktivity_event', 5 );

function displayPosts() {
	setTimeout(() => {
		blogContainer.innerHTML = blogPosts.join('');
		tvContainer.innerHTML = shows.join('');
	}, 3000);
}
displayPosts();
