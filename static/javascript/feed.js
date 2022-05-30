class Feed {

    async render(data, posts) {


        /* ====== CONTAINER */

        let wrapper = document.createElement("div");
        wrapper.className = "content-wrapper";

        let postWidget = document.createElement("div");
        postWidget.className = "post-new-widget";
        postWidget.id = "P" + data.id;

        let container = document.createElement("div");
        container.className = "container";

        /* ====== HEADER */
        let postUser = document.createElement("div");
        postUser.className = "post-user";

        let userImage = document.createElement("div");
        userImage.className = "user-image";

        let userImagePicture = document.createElement("img");
        userImagePicture.src = data.author.profile_image;

        let userDetails = document.createElement("div");
        userDetails.className = "user-details";

        let userDetailsName = document.createElement("div");
        userDetailsName.className = "user-name";
        userDetailsName.innerHTML = `${data.author.first_name} ${data.author.last_name}`;

        let userDetailsTags = document.createElement("div");
        userDetailsTags.className = "user-tags";

        let postTime = document.createElement("div");
        postTime.className = "post-time";
        postTime.innerHTML = `${data.time}`;

        let separator = document.createElement("div");
        separator.className = "separator";

        /* ====== CONTENT */
        let postContent = document.createElement("div");
        postContent.className = "post-content-wrapper";

        let paragraphs = data.contents;
        paragraphs.forEach(paragraph => {
            let p = document.createElement("p");
            p.innerHTML = paragraph.paragraph;
            postContent.appendChild(p);
        });

        /* ====== MERGES */
        userImage.appendChild(userImagePicture);
        userDetails.appendChild(userDetailsName);
        userDetails.appendChild(userDetailsTags);
        postUser.appendChild(userImage);
        postUser.appendChild(userDetails);
        postUser.appendChild(postTime);

        container.appendChild(postUser);
        container.appendChild(separator);
        container.appendChild(postContent);


        postWidget.appendChild(container);
        wrapper.appendChild(postWidget);


        posts.appendChild(wrapper);

    }

    async get(id) {
        axios.get(`${config.api.url}/posts/index?classroom=${id}`)
            .then(async (response) => {
                let loader = document.getElementById("posts-loader");
                let posts = document.getElementById("load-posts");
                loader.style.display = "none";
                posts.innerHTML = "";
                let data = await response.data;
                data.forEach(repo => {
                    this.render(repo, posts);
                });
            }).catch((error) => {
            console.log(error);
        });
        return null;
    }

    async index() {
        await axios.get(`${config.api.url}/posts/index`)
            .then(async (response) => {
                let loader = document.getElementById("posts-loader");
                let posts = document.getElementById("load-posts");
                loader.style.display = "none";
                posts.innerHTML = "";
                let data = await response.data;
                data.forEach(repo => {
                    this.render(repo, posts);
                });
            }).catch((error) => {
                console.log(error);
            });
    }



}