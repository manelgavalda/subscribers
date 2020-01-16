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
					<v-col cols="3" sm="3">
		        <v-btn depressed color="primary">Create Subscriber</v-btn>
	    		</v-col>

				</v-row>
			</v-container>

      <v-container
        class="fill-height"
        fluid
      >
      	<subscribers-list></subscribers-list>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      drawer: null,
    }),
  }
</script>
