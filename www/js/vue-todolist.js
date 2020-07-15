var tasks = new Vue({
    el: '#todolist',
    delimiters: ['((', '))'],

    data: {
        title: '',
        items: ''
    },

    methods: {
        request: function(params) {
            axios({
                method: 'post',
                url: '/php/action.php',
                responseType: 'json',
                data: params
            }).then((response) => {
                //console.log(response.data);
                if(params.action == 'add') {
                    this.insertNewTask(response.data);
                }

                if(params.action == 'getlist') {
                    this.insertList(response.data);
                }
            }).catch(function (error) {
                console.log(error);
            });
        },

        addTask: function () {
            let params = {
                action: 'add',
                title: this.title
            };
            this.request(params);
        },

        insertNewTask: function(response) {
            this.title = '';
            this.setFocus();

            if(response.success) {
                let list = document.getElementById('items');
                let html = list.innerHTML;
                list.innerHTML = response.html + html;
            }
        },

        insertList: function(response) {
            if(response.success) {
                let list = document.getElementById('items');
                list.innerHTML = response.html;
            }
        },

        getList: function() {
            let params = {
                action: 'getlist'
            };

            this.request(params);
        },

        setFocus: function () {
            let input = document.querySelector('#todolist input[name="title"]');
            input.focus();
        }
    }
});

tasks.setFocus();
tasks.getList();