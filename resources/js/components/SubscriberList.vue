<template>
  <v-container>
    <v-card
      min-width="450"
      height="500px"
      style="overflow-y: auto"
    >
      <v-toolbar color="#55A256" dark>
        <v-toolbar-title>Subscriber List</v-toolbar-title>
      </v-toolbar>
      <v-list>
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
            <v-col>
              <v-chip
                dark
                :color="subscriberStates[subscriber.state]"
                v-text="subscriber.state"
              ></v-chip>
            </v-col>
          </v-list-item-content>
          <v-list-item-content>
            <v-list-item-subtitle>
              <ul>
                <li v-for="field in subscriber.fields">
                  {{ `${field.title} (${field.type}): ${field.value}` }}
                </li>
              </ul>
            </v-list-item-subtitle>
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
