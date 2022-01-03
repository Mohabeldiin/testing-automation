<template>
    <div>
        <div  v-if="state === 'demoLoaded'" class="mh-importer__loaded">
            {{ getString('demo_loaded') }}
        </div>

        <div v-if="state === 'showDemo'">
            <h2>{{ getString('available_demos') }}</h2>

            <div v-for="demo in demos">
                <div class="mh-importer__card">
                    <div class="mh-importer__image-wrapper">
                        <img :src="demo.image" :alt="demo.name">
                    </div>
                    <h3>{{ demo.name }}</h3>
                    <div class="mh-importer__card-info">
                        <ul>
                            <li v-for="feature in demo.features">{{ feature }}</li>
                        </ul>
                    </div>
                    <button v-if="!loadingDemo" class="button button-primary" @click="loadDemo(demo)">
                        {{ getString('load_demo') }}
                    </button>
                    <a :href="demo.url" class="button">{{ getString('demo_online') }}</a>
                </div>
            </div>

            <div class="mh-importer__php">
                <div class="mh-importer__speed">
                    <div>
                        <p>
                            This importer has 2 modes - "standard" and "slow". Switch to "slow" if "standard" fails (it's rare but sometimes hosting do not allow to upload files very fast)
                        </p>
                        <select v-model="mode">
                            <option value="normal">Standard</option>
                            <option value="slow">Slow</option>
                        </select>
                        <div>
                            <h3>Some Windows servers</h3>
                            Try to visit this URL: http://yourwordpresslocation/wp-content/plugins/myhome-importer/demos/default/meta.json . If it returns error 404, it means that your server do not recognize .json files. You can contact your hosting provider and ask how to turn this option on.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="state === 'loadingDemo'" class="mh-importer-loading-wrapper">
            <h3>Importing demo...</h3>

            <ul class="mh-importer-loading">
                <li>{{ getString('posts') }}: <span>{{ steps.posts.loaded }}%</span></li>
                <li>{{ getString('comments') }}: <span>{{ steps.comments.loaded }}%</span></li>
                <li>{{ getString('users') }}: <span>{{ steps.users.loaded }}%</span></li>
                <li>{{ getString('media') }}: <span>{{ steps.media.loaded }}%</span></li>
                <li>{{ getString('terms') }}: <span>{{ steps.terms.loaded }}%</span></li>
                <li>{{ getString('term_taxonomy') }}: <span>{{ steps.term_taxonomy.loaded }}%</span></li>
                <li>{{ getString('term_relationships') }}: <span>{{ steps.term_relationships.loaded }}%</span></li>
                <li>{{ getString('term_meta') }}: <span>{{ steps.term_meta.loaded }}%</span></li>
                <li>{{ getString('options') }}: <span>{{ steps.options.loaded }}%</span></li>
                <li>{{ getString('locations') }}: <span>{{ steps.locations.loaded }}%</span></li>
                <li>{{ getString('attributes') }}: <span>{{ steps.attributes.loaded }}%</span></li>
                <li>{{ getString('sliders') }}: <span>{{ steps.sliders.loaded }}%</span></li>
                <li>{{ getString('redux') }}: <span>{{ steps.redux.loaded }}%</span></li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                errorMessage: '',
                timer: 0,
                mode: 'normal',
                state: 'showDemo',
                demoKey: '',
                steps: {
                    posts: {normal: 50, slow: 25, loaded: 0},
                    comments: {normal: 0, slow: 0, loaded: 0},
                    users: {normal: 0, slow: 5, loaded: 0},
                    media: {normal: 1, slow: 1, loaded: 0},
                    terms: {normal: 100, slow: 50, loaded: 0},
                    term_taxonomy: {normal: 100, slow: 50, loaded: 0},
                    term_relationships: {normal: 100, slow: 50, loaded: 0},
                    term_meta: {normal: 100, slow: 50, loaded: 0},
                    options: {normal: 20, slow: 10, loaded: 0},
                    locations: {normal: 0, slow: 10, loaded: 0},
                    attributes: {normal: 0, slow: 0, loaded: 0},
                    sliders: {normal: 0, slow: 1, loaded: 0},
                    redux: {normal: 0, slow: 0, loaded: 0}
                }
            }
        },
        props: {
            url: {
                type: String
            },
            translations: {
                type: Object
            },
            demos: {
                type: Array
            }
        },
        computed: {
            timeLeft () {
              return this.timer;
            },
            postsProgress () {
                if (this.postsLoaded === 0) {
                    return this.getString('waiting');
                } else {
                    return this.postsLoaded + '%';
                }
            }
        },
        methods: {
            loadDemo (demo) {
            	jQuery('.mh-server').hide();
            	jQuery('.mh-info-database').hide();
                this.demo = demo;
                this.state = 'loadingDemo';
                jQuery.getJSON(this.demo.meta, function (data) {
                    window.scrollTo(0, 0);
                    this.demoMeta = data;
                    this.init();
                }.bind(this))
            },
            init () {
                jQuery.ajax({
                    method: 'POST',
                    url: this.url,
                    data: {action: 'myhome_importer_init'}
                }).done(function () {
                    this.nextStep('first');
                }.bind(this));
            },
            nextStep (lastStep) {
                let nextStep = false;
                jQuery.each(this.steps, function (stepKey, step) {
                    if (lastStep === 'first') {
                        this.step(stepKey, 0);
                        return false;
                    }
                    if (nextStep === true) {
                        this.step(stepKey, 0);
                        return false;
                    }
                    if (stepKey === lastStep && stepKey !== 'redux') {
                        nextStep = true;
                    }
                }.bind(this));

                if (nextStep === false && lastStep !== 'first') {
                    this.finishLoading();
                }
            },
            step (key, start) {
                let data = {
                    action: 'myhome_importer_add_' + key,
                    demoKey: this.demo.key
                };

                let limit = this.steps[key][this.mode];
                if (limit === 0) {
                    data.start = 0;
                    data.limit = this.demoMeta[key];
                } else {
                    data.start = start;
                    data.limit = start + limit;
                    if (data.limit > this.demoMeta[key]) {
                        data.limit = this.demoMeta[key];
                    }
                }

                jQuery.ajax({
                    method: 'POST',
                    url: this.url + '?key=' + key,
                    data: data
                })
                .done(function () {
                    this.steps[key].loaded = Math.round(data.limit / this.demoMeta[key] * 100);
                    if (data.limit < this.demoMeta[key]) {
                        this.step(key, data.limit);
                    } else {
                        this.nextStep(key);
                    }
                }.bind(this))
                .fail(function () {
                    this.errorMessage = this.getString('error_message');
                    this.timer = 30;
                    let timer = setInterval(function () {
                        if (this.timer > 0) {
                            this.timer--;
                        } else {
                            this.step(key, start);
                            clearInterval(timer)
                        }
                    }.bind(this), 1000);
                }.bind(this))
            },
            finishLoading () {
                jQuery.ajax({
                    method: 'POST',
                    url: this.url,
                    data: {action: 'myhome_importer_clear_cache' }
                }).done(function () {
                    this.state = 'demoLoaded';
                }.bind(this));
            },
            getString (key) {
                if (typeof this.translations[key] !== 'undefined') {
                    return this.translations[key];
                } else {
                    return '';
                }
            }
        }
    }
</script>