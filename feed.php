<link rel="stylesheet" href="styles/feed.css">
<section class="feed">
    <h2 class="hash-title"><a href="#feed">Feed</a></h2>

    <div id="feed"></div>

    <div id="loading" class="info-container">
        <svg width="40" height="40" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle class="static-ring" cx="50" cy="50" r="40" fill="none" stroke-width="15" />
            <circle class="rotating-ring" cx="50" cy="50" r="40" fill="none" stroke-width="15" stroke-dasharray="40 220" />
        </svg>
    </div>

    <div id="all" class="info-container hidden">
        <p>All posts loaded</p>
        <p><a class="button filled button-slim" href="">Refresh</a></p>
    </div>


    <div id="error" class="info-container hidden">
        <p>Error loading posts</p>
        <p><a class="button filled button-slim" href="">Refresh</a></p>
    </div>


    <script>

        function isColorDark(hexColor) {
            hexColor = hexColor.replace(/^#/, '');
            const r = parseInt(hexColor.substr(0, 2), 16);
            const g = parseInt(hexColor.substr(2, 2), 16);
            const b = parseInt(hexColor.substr(4, 2), 16);
            const luminance = (0.2126 * r + 0.7152 * g + 0.0722 * b) / 255;
            return luminance < 0.5;
        }

        function getContrastingTextColor(hexColor) {
            return isColorDark(hexColor) ? '#FFFFFF' : '#000000';
        }

        function mdSimpleFormat(text) {
            // Replace bold (**text**)
            text = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');

            // Replace italic (*text*)
            text = text.replace(/\*(.*?)\*/g, '<i>$1</i>');

            // Replace strikethrough (~~text~~)
            text = text.replace(/~~(.*?)~~/g, '<s>$1</s>');

            // Replace blockquotes (> text)
            text = text.replace(/^> (.*?)(\n|$)/gm, '<blockquote>$1</blockquote>');

            // Replace unordered lists (- item or * item)
            text = text.replace(/^[-*] (.*?)(\n|$)/gm, '<li>$1</li>');
            text = text.replace(/(<li>.*<\/li>)/gms, '<ul>$1</ul>'); // Wrap consecutive <li> in <ul>

            // Wrap all remaining text blocks that are not blockquotes or lists with <p>
            text = text.replace(/^(?!<blockquote>|<li>|<\/?ul>|<\/?blockquote>)(.*?)(\n|$)/gm, (match, content) => {
                return content.trim() ? `<p>${content.trim()}</p>` : '';
            });

            // Remove extra line breaks between <ul> or <blockquote> elements
            text = text.replace(/<\/ul>\s*<ul>/g, '');
            text = text.replace(/<\/blockquote>\s*<blockquote>/g, '<br>');

            return text.trim();
        }

        let page = 0; // Current page number
        let loading = false; // Prevent duplicate loading
        let error = false; // Error loading posts
        let all = false; // All posts loaded

        const feed = document.getElementById('feed');

        // Function to load posts
        async function loadPosts() {
            if (loading || all || error) return;
            loading = true;

            try {
                const response = await fetch(`api_fetch_posts.php?page=${page}`);
                const posts = await response.json();
                if (posts.length > 0) {
                    page++;

                    posts.forEach(post => {
                        const postElement = document.createElement('div');
                        postElement.classList.add('post');

                        let community_badge, community_avatar;

                        if(post.community_id != null) {
                            community_avatar = `<span class="community-avatar" style="background-color: ${post.hex_color}"></span>`;
                            community_badge = `in <a class="badge" href="community.php?id=${post.community_id}" style="
                                background-color: ${post.hex_color};
                                color: ${getContrastingTextColor(post.hex_color)};
                            ">${post.hex_color}</a>`;
                        }

                        postElement.innerHTML = `
                            <div class="author-bar">
                                <span class="author">
                                    <span class="author-icon">
                                        ${community_avatar ?? ""}
                                        <img class="author-pfp" src="uploads/${post.user_id}/${post.profile_picture}">
                                    </span>
                                    <span class="author-text">
                                        <span><a class="user-name" href="user.php?id=${post.user_id}">${post.username}</a>
                                        ${community_badge ?? ""}
                                        </span>
                                        <span class="time-passed">${post.time_passed}</span>
                                    </span>
                                </span>
                            </div>
                            <div class="post-content">
                                <h3 class="post-title"><a href="post.php?id=${post.id}">${post.title}</a></h3>
                                <div class="post-text">${mdSimpleFormat(post.text)}</div>
                                <div class="toolbar">
                                    <div class="actions left">
                                        <?php if (isset($user_id)): ?>
                                            <div class="vote-block" data-id="${post.id}" data-type="post">
                                                <button class="vote-btn upvote" data-value="1"><i class="ni-angle-up"></i></button>
                                                <span class="vote-rating">${post.rating}</span>
                                                <button class="vote-btn downvote" data-value="-1"><i class="ni-angle-down"></i></button>
                                            </div>
                                        <?php else: ?>
                                            <a class="action" href="login.php"><i class="ni-arrow-right"></i> Log in to vote</a>
                                        <?php endif; ?>
                                        <a class="action" href="post.php?id=${post.id}#comments"><i class="ni-comment"></i> Comments</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        // <div class="actions right">
                        //     <span class="action"><i class="ni-bookmark"></i> Save</span>
                        //     <span class="action"><i class="ni-connection"></i> Share</span>
                        // </div>
                        feed.appendChild(postElement);
                        const phElement = document.createElement('span');
                        phElement.classList.add('ph-horisontal');
                        feed.appendChild(phElement);
                    });

                    document.querySelectorAll('.vote-btn').forEach(button => {
                        button.onclick = async function () {
                            console.log("sdfdsbd");
                            const voteBlock = this.closest('.vote-block');
                            const id = voteBlock.dataset.id;
                            const type = voteBlock.dataset.type;
                            const value = this.dataset.value;

                            try {
                                // Send GET request
                                const response = await fetch(`rate.php?id=${id}&type=${type}&value=${value}`);
                                const result = await response.json();

                                if (result.error) {
                                    console.error('Error:', result.message || 'An error occurred.');
                                    voteBlock.querySelector('.vote-rating').textContent = '-';
                                } else {
                                    voteBlock.querySelector('.vote-rating').textContent = result.total_rating;
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                voteBlock.querySelector('.vote-rating').textContent = '-';
                            }
                        }
                    });

                } else {
                    all = true;
                    document.getElementById('loading').classList.add("hidden");
                    document.getElementById('all').classList.remove("hidden");
                }

            } catch (error) {
                error = true;
                console.error('Error loading posts:', error);
                document.getElementById('loading').classList.add("hidden");
                document.getElementById('error').classList.remove("hidden");
            } finally {
                loading = false;
            }
        }

        // Load initial posts
        loadPosts();

        // Infinite scroll

        // document.addEventListener('scroll', () => {
        //     let scrollHeight = Math.max(
        //         document.body.scrollHeight, document.documentElement.scrollHeight,
        //         document.body.offsetHeight, document.documentElement.offsetHeight,
        //         document.body.clientHeight, document.documentElement.clientHeight
        //     );
        //     if (document.documentElement.scrollTop + document.documentElement.clientHeight >= scrollHeight - 10) {
        //     }
        // });

        window.addEventListener("scroll", () => {
            // Calculate the scroll position
            const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
            const clientHeight = document.documentElement.clientHeight || window.innerHeight;

            // console.log({ scrollTop, clientHeight, scrollHeight });
            
            // Check if the user has scrolled to the end
            if (scrollTop + clientHeight >= scrollHeight - 20) {
                loadPosts();
            }
        });



    </script>

</section>
