<template>
  <v-app>
    <v-app-bar
      app
      dark
      color="#55A255"
    >
      <v-toolbar-title><b>MailerLite Subscribers</b></v-toolbar-title>

      <v-spacer></v-spacer>

      <v-app-bar-nav-icon />
    </v-app-bar>

    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
      	<subscribers-list
					:subscribers="subscribers"
          @addSubscriber="addSubscriber"
          @changeSubscriber="changeSubscriber"
					@removeSubscriber="removeSubscriber"
      	></subscribers-list>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
    	subscribers: []
    }),
    mounted() {
    	this.getSubscribers()
    },
    methods: {
    	getSubscribers() {
    		axios.get('/api/subscribers')
    			.then(({data}) => this.subscribers = data)
    	},
    	addSubscriber(subscriber) {
    		this.subscribers.push(subscriber)
    	},
      changeSubscriber(data) {
        this.subscribers[data.index] = data.subscriber
      },
    	removeSubscriber(index) {
    		this.subscribers.splice(index, 1)
    	},
    }
  }
</script>
