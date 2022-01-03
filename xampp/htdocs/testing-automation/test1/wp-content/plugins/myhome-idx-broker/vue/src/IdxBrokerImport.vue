<template>
  <div>
    <div v-if="state === 'init'">
      <button class="button button-primary idx-button-big idx-button-big" @click="onClick">
        Import Listings
      </button>
    </div>

    <div class="mh-info-vue-idx-check" v-if="state === 'check'">
      <i class="fa fa-refresh fa-spin" style="margin-right:6px; display:inline-block;"></i> Connecting....
    </div>

    <div class="mh-info-vue-idx-work" v-if="state === 'work'">
      <span><i class="fa fa-refresh fa-spin" style="margin-right:6px; display:inline-block;"></i> </span>
      <span>{{ currentProperty }} / {{ propertiesNumber }}</span>
    </div>

    <div  class="mh-info-vue-idx-msg" v-if="msg !== ''">
      <p>{{ msg }}</p>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        state           : 'init',
        msg             : '',
        propertiesNumber: 0,
        currentProperty : 0
      }
    },
    methods: {
      onClick() {
        this.msg = '';
        this.state = 'check';

        this.$http.post(ajaxurl + "?id=" + Math.floor(Math.random() * 100000), {action: 'myhome_idx_broker_import_init'}, {emulateJSON: true}).then((response) => {
          let data = response.body;
          this.msg = data.msg;

          if (data.start) {
            this.state = 'work';
            this.propertiesNumber = data.found;
            this.startJob();
          } else {
            this.state = 'init';
          }
        }, () => {
          this.state = 'init';
        });
      },
      startJob() {
        this.$http.post(ajaxurl + "?id=" + Math.floor(Math.random() * 100000), {action: 'myhome_idx_broker_import_job'}, {emulateJSON: true}).then((data) => {
          if (data.body.thumbnails) {
            this.generateThumbnails();
          } else {
            this.nextJob();
          }
        }, () => {
          this.state = 'init';
        });
      },
      generateThumbnails() {
        this.$http.post(ajaxurl + "?id=" + Math.floor(Math.random() * 100000), {action: 'myhome_idx_broker_generate_thumbnails'}, {emulateJSON: true}).then((data) => {
          if (data.body.next === true) {
            this.generateThumbnails();
          } else {
            this.nextJob();
          }
        });
      },
      nextJob() {
        this.currentProperty++;
        if (this.currentProperty < this.propertiesNumber) {
          this.startJob();
        } else {
          this.msg = 'Synchronization finished';
          this.state = 'init';
        }
      }
    }
  }
</script>
