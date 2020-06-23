<template>
    <div class="div">
        <h2>Posts:</h2>
        <ul class="nav justify-content-end">
            <button type="button" class="btn btn-outline-dark " data-toggle="modal" data-target="#post">Add post
            </button>
        </ul>

        <!-- Modal -->
        <div class="modal fade" id="post" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addPost()">
                            <div class="form-group">
                                <label for="title">Post title</label>
                                <input type="text"  class="form-control" id="title"
                                       placeholder="Title" v-model="post.title">
                            </div>
                            <div class="form-group">
                                <label for="body">Post body</label>
                                <textarea class="form-control"  id="body" rows="3"
                                          v-model="post.body"> </textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-dark">Save post</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url }]" class="page-item">
                    <a class="page-link" href="#"
                       @click="fetchPosts(pagination.prev_page_url)">Previous</a></li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#"
                >Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url }]" class="page-item">
                    <a class="page-link" href="#"
                       @click="fetchPosts(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
        <div class="row">
            <div class="card m-2" v-for="post in posts" v-bind:key="post.id" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{post.title}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                    <p class="card-text">{{post.body}}</p>
                    <button @click="editPost(post)" type="button" data-toggle="modal" data-target="#edit"
                            class="btn btn-outline-secondary">Edit
                    </button>
                    <button @click="deletePost(post.id)" type="button" class="btn btn-outline-danger">Delete</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editPostForm()">
                            <div class="form-group">
                                <label for="title">Post title</label>
                                <input type="text" name="title" class="form-control" id="title_edit"
                                       placeholder="Title" v-model="post.title">
                            </div>
                            <div class="form-group">
                                <label for="body">Post body</label>
                                <textarea class="form-control" name="body" id="body_edit" rows="3"
                                          v-model="post.body"> </textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-dark">Save post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    export default {
        data() {
            return {
                posts: [],
                post: {
                    id: '',
                    title: '',
                    body: '',
                },
                pagination: {},
                edit: false
            }
        },
        created() {
            this.fetchPosts();
        },


        methods: {
            //без пагінації
            // fetchPosts(){
            //     fetch('api/posts')
            //         .then(res => res.json())
            //         .then(res =>{
            //             this.posts = res.data
            //         });
            // },
            fetchPosts(page_url) {
                let vm = this;
                page_url = page_url || '/api/posts';
                fetch(page_url).then
                (res => res.json())
                    .then(res => {
                        this.posts = res.data;
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            addPost() {
                fetch('api/post',
                    {
                        method: 'post',
                        body: JSON.stringify(this.post),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.post.title = '';
                        this.post.body = '';
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err));
            },
            deletePost(id) {
                fetch(`api/post/${id}`, {
                    method: 'delete'
                })
                    .then(res => res.json())
                    .then(data => {
                        alert('Видалено')
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err));
            },
            editPost(post) {
                this.post.id = post.id;
                this.post.title = post.title;
                this.post.body = post.body;
            },
            editPostForm() {
                fetch('api/post',
                    {
                        method: 'put',
                        body: JSON.stringify(this.post),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.post.title = '';
                        this.post.body = '';
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err));
            }

        }
    }

</script>
