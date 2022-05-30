class Post {


    async save(id, post_data) {
        axios.post(`${config.api.url}/posts/save/${id}`, {
            id,
            post_data
        }).then(async (response) => {
            let data = await response.data;
        }).catch((error) => {
            console.log(error);
        });
        return null;
    }


}