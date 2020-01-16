<template>
  <v-app>
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list dense>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-home</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Subscribers</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-contact-mail</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Fields</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="#55A255"
      dark
    >
      <v-toolbar-title><b>MailerLite</b></v-toolbar-title>

      <v-spacer></v-spacer>

      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
    </v-app-bar>

    <v-content>
    	<v-container
  			fluid
			>
			  <v-row align="center">
			  	<v-col cols="9" sm="9">
						Subscribers
					</v-col>

        	<v-btn
        		dark
        		color="#F0821C"
        		@click="openCreateDialog"
      		>Create Subscriber</v-btn>
				</v-row>
			</v-container>

      <v-container
        class="fill-height"
        fluid
      >
      	<subscribers-list
					:subscribers="subscribers"
					@openEditDialog="openEditDialog"
					@removeSubscriber="removeSubscriber"
      	></subscribers-list>
      </v-container>
    </v-content>

    <subscriber-dialog
	    ref="dialog"
	    @saveSubscriber="saveSubscriber"
	  ></subscriber-dialog>

  </v-app>
</template>

<script>
  export default {
    data: () => ({
      drawer: null,
    	subscribers: []
    }),
    mounted() {
    	this.getSubscribers();
    },
    methods: {
    	getSubscribers() {
    		axios.get('/api/subscribers')
    			.then(({data}) => this.subscribers = data);
    	},
    	openCreateDialog() {
    		const form = {
    			method: 'post',
    			url: '/api/subscribers',
    			data: {
	    			id: '0',
	    			name: '',
	    			email: '',
	    			state: 'unconfirmed'
	    		}
    		};

    		this.openDialog(form);
    	},
    	openEditDialog(data) {
    		const form = {
    			method: 'put',
    			url: `/api/subscribers/${data.subscriber.id}`,
    			data: data.subscriber
    		};

    		this.openDialog(form);
    	},
    	openDialog(form) {
    		Vue.nextTick(() => this.$refs.dialog.open(form))
    	},
    	removeSubscriber(index) {
    		this.subscribers.splice(index, 1);
    	},
    	saveSubscriber(subscriber) {
    		console.log(subscriber);
    		// this.subscribers.push(subscriber);
    	}
    }
  }
</script>
