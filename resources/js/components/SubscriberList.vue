<template>
  <v-container>
    <v-card
      class="mx-auto mt30"
      max-width="1000"
    >
      <v-toolbar color="#55A256" dark>
        <v-toolbar-title>Subscribers</v-toolbar-title>
      </v-toolbar>
      <v-list subheader>
        <v-list-item
          v-for="(subscriber, index) in subscribers"
          :key="index"
        >
          <v-list-item-content>
            <v-list-item-title
              v-text="subscriber.name"
            ></v-list-item-title>
            <v-list-item-subtitle
              v-text="subscriber.email"
            ></v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-content>
            <v-chip
              :color="subscriberStates[subscriber.state]"
              v-text="subscriber.state"
            ></v-chip>
          </v-list-item-content>
          <v-list-item-icon>
            <v-icon
            	@click="editSubscriber(subscriber, index)"
          	>mdi-pencil</v-icon>
            <v-icon
            	@click="removeSubscriber(subscriber.id, index)"
          	>mdi-delete</v-icon>
          </v-list-item-icon>
        </v-list-item>
      </v-list>
    </v-card>
  </v-container>
</template>
<script>
  export default {
    props: ['subscribers', 'subscriberStates'],
    methods: {
      editSubscriber(subscriber, index) {
        this.$emit('editSubscriber', {subscriber, index})
      },
    	removeSubscriber(id, index) {
    		axios.delete(`/api/subscribers/${id}`)
    			.then(() => this.$emit('removeSubscriber', index))
    	}
    }
  }
</script>
<style>
  .mt30 {
    margin-top: 30px
  }
</style>
