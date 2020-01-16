<template>
  <v-row justify="center">
	  <v-dialog v-model="dialog" persistent max-width="600px">
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
	        <v-btn color="blue darken-1" text @click="close">Close</v-btn>
	        <v-btn
	          text
	          color="blue darken-1"
	          @click="editSubscriber"
	          :disabled="saving"
	        >Save</v-btn>
	      </v-card-actions>
	    </v-card>
	  </v-dialog>
	</v-row>
</template>
<script>
	export default {
		props: ['dataSubscriber'],
		data: () => ({
			subscriber: {
				name: null,
				email: null,
				state: 'unconfirmed'
			},
			form: {
				data: {}
			},
      errors: [],
      dialog: false,
      saving: false
		}),
		mounted() {
			this.subscriber = this.dataSubscriber;
		},
		methods: {
      open(form) {
      	this.form = form;

      	this.subscriber = form.data;

        this.dialog = true;
      },
      close() {
        this.dialog = false;
      },
			editSubscriber() {
        this.saving = true;

        axios[this.form.method](this.form.url, this.form.data)
          .then(this.saveSubscriber)
          .catch(this.getErrors);
      },
      saveSubscriber({data}) {
        this.$emit('saveSubscriber', data);

        this.subscriber = {
          name: '',
          email: '',
          state: 'unconfirmed'
        };

        this.close();

        this.errors = [];
        this.saving = false;
      },
      getErrors({response}) {
        this.errors = response.data.errors;

        this.saving = false;
      }
		}
	}
</script>
