<template>
  <v-card
    min-width="500"
    class="mx-auto"
  >
    <v-toolbar
      color="deep-purple accent-4"
      dark
    >
      <v-toolbar-title>Subscribers</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
    </v-toolbar>

    <v-list subheader>
      <v-subheader>Recent chat</v-subheader>

      <v-list-item
        v-for="(subscriber, index) in subscribers"
        :key="subscriber.id"
        @click=""
      >
        <v-list-item-content>
          <v-list-item-title v-text="subscriber.name"></v-list-item-title>
        </v-list-item-content>
        <v-list-item-content>
          <v-list-item-title v-text="subscriber.email"></v-list-item-title>
        </v-list-item-content>
        <v-list-item-content>
          <v-list-item-title v-text="subscriber.state"></v-list-item-title>
        </v-list-item-content>

        <v-list-item-icon>
          <v-icon>mdi-pencil</v-icon>
          <v-icon @click="removeSubscriber(subscriber.id, index)">mdi-delete</v-icon>
        </v-list-item-icon>
      </v-list-item>
    </v-list>
  </v-card>
</template>
<script>
  export default {
    data: () => ({
      subscribers: [],
    }),
    mounted() {
    	this.getSubscribers()
    },
    methods: {
    	getSubscribers() {
    		axios.get('/api/subscribers')
    			.then(({data}) => this.subscribers = data);
    	},
    	removeSubscriber(id, index) {
    		axios.delete(`/api/subscribers/${id}`)
    			.then(this.subscribers.splice(index, 1));
    	}
    }
  }
</script>
