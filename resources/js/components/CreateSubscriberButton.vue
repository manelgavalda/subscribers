<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="600px">
      <template v-slot:activator="{ on }">
        <v-btn color="#F0821C" dark v-on="on">Create Subscriber</v-btn>
      </template>
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
          <v-btn color="blue darken-1" text @click="closeDialog">Close</v-btn>
          <v-btn
            text
            color="blue darken-1"
            @click="saveSubscriber"
            :disabled="saving"
          >Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
  export default {
    data: () => ({
      subscriber: {
        name: '',
        email: '',
        state: 'unsubscribed'
      },
      errors: [],
      dialog: false,
      saving: false
    }),
    methods: {
      closeDialog() {
        this.dialog = false
      },
      saveSubscriber() {
        this.saving = true;

        axios.post('/api/subscribers', this.subscriber)
          .then(this.createSubscriber)
          .catch(this.getErrors);
      },
      createSubscriber({data}) {
        this.$emit('createSubscriber', data);

        this.subscriber = {
          name: '',
          email: '',
          state: 'unsubscribed'
        };

        this.errors = [];
        this.saving = false;

        this.closeDialog();
      },
      getErrors({response}) {
        this.errors = response.data.errors;

        this.saving = false;
      }
    }
  }
</script>
