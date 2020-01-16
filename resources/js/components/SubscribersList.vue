<template>
  <v-card
    min-width="500"
    class="mx-auto"
  >
    <v-toolbar
      color="#55A256"
      dark
    >
      <v-toolbar-title>Subscribers</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
    </v-toolbar>

    <v-list subheader>
      <v-list-item
        v-for="(subscriber, index) in subscribers"
        :key="subscriber.id"
        @click=""
      >
        <v-list-item-content>
          <v-list-item-title v-text="subscriber.name"></v-list-item-title>
          <v-list-item-subtitle v-text="subscriber.email"></v-list-item-subtitle>
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
    props: ['subscribers'],
    methods: {
    	removeSubscriber(id, index) {
    		axios.delete(`/api/subscribers/${id}`)
    			.then(this.$emit('removeSubscriber', index));
    	}
    }
  }
</script>
