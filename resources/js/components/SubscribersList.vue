<template>
  <v-container fluid>
    <v-card>
      <v-card-title>
        <span class="headline">Subscriber Profile</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12">
              <v-text-field
                required
                label="Name*"
                v-model="subscriber.name"
              ></v-text-field>
              <p v-for="error in errors.name">
                <ul>
                  <li v-text="error"></li>
                </ul>
              </p>
            </v-col>
            <v-col cols="12">
              <v-text-field
                required
                label="Email*"
                v-model="subscriber.email"
              ></v-text-field>
              <p v-for="error in errors.email">
                <ul>
                  <li v-text="error"></li>
                </ul>
              </p>
            </v-col>
            </v-col>
            <v-col cols="12">
              <v-select
                v-model="subscriber.state"
                :items="['active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed']"
                label="State*"
                required
              ></v-select>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text>Cancel</v-btn>
        <v-btn
          text
          color="blue darken-1"
          @click="createSubscriber"
          :disabled="saving"
        >Save</v-btn>
      </v-card-actions>
    </v-card>
    <v-card>
      <v-toolbar color="#55A256" dark>
        <v-toolbar-title>Subscribers</v-toolbar-title>
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
            <v-icon
            	@click="openEditDialog(subscriber, index)"
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
    props: ['subscribers'],
    data: () => ({
      errors: [],
      saving: false,
      subscriber: {
        name: '',
        email: '',
        state: 'unconfirmed'
      }
    }),
    methods: {
    	openEditDialog(subscriber, index) {
    		this.$emit('openEditDialog', {subscriber, index});
    	},
    	removeSubscriber(id, index) {
    		axios.delete(`/api/subscribers/${id}`)
    			.then(this.$emit('removeSubscriber', index));
    	},
      createSubscriber() {
        this.saving = true;

        axios.post('/api/subscribers', this.subscriber)
          .then(this.addSubscriber)
          .catch(this.showErrors)
          .then(this.saving = false);
      },
      addSubscriber({data}) {
        this.$emit('addSubscriber', data);

        this.subscriber = {
          name: '',
          email: '',
          state: 'unconfirmed'
        };
      },
      showErrors({response}) {
        this.errors = response.data.errors;
      }
    }
  }
</script>
