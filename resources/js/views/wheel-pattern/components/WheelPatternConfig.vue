<template>
  <el-form ref="form" :model="form" label-position="top" class="wheel-pattern-config">

    <wheel-pattern-config-action bottom-line :action="formAction" @handleUpdate="handleUpdate" @handleClose="handleClose" />

    <!-- Base Info -->
    <form-section :title="$t('wheel.pattern.config.baseInfo')">
      <el-col>
        <el-col style="padding: 0 15px" :lg="7" :xl="6">
          <el-form-item :label="$t('wheel.pattern.config.patternName')" prop="name" :rules="rules.name">
            <el-input v-model="form.name" :aria-placeholder="$t('wheel.pattern.config.patternName')" @change="handleNameChange" />
          </el-form-item>
        </el-col>
        <el-col style="padding: 0 15px" ::lg="7" :xl="6">
          <el-form-item :label="$t('wheel.pattern.config.type')">
            <el-radio-group v-model="form.type">
              <el-radio label="Text">Ticket</el-radio>
              <el-radio disabled label="Image">Image</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-col>
    </form-section>

    <!-- Bonus Info -->
    <form-section :title="$t('wheel.pattern.config.bonusInfo')">
      <el-col style="margin: 0 15px" :lg="7" :xl="6">
        <el-form-item :label="$t('wheel.pattern.config.bonusEnable')">
          <el-switch
            v-model="form.bonus_enable"
            inactive-color="#ff4949"
            :active-value="1"
            :inactive-value="0"
          />
        </el-form-item>
      </el-col>
      <el-col style="margin: 0 15px" :lg="7" :xl="6">
        <el-form-item :label="$t('wheel.pattern.config.bonusIncrement')">
          <el-switch
            v-model="form.bonus_auto_increment"
            :disabled="!!!form.bonus_enable"
            inactive-color="#ff4949"
            :active-value="1"
            :inactive-value="0"
          />
        </el-form-item>
      </el-col>
      <el-col style="margin: 0 15px" :lg="10" :xl="6">
        <el-form-item :label="$t('wheel.pattern.config.bonusFixed')">
          <el-input-number
            v-model="form.bonus_fixed"
            :disabled="!!form.bonus_auto_increment || !!!form.bonus_enable"
            :min="form.bonus_min"
            :max="form.bonus_max"
            :step="10"
            :placeholder="$t('wheel.pattern.config.bonusFixed')"
            @change="updateCurrentBonus"
          />
        </el-form-item>
      </el-col>
      <el-col style="margin: 0 15px" :lg="10" :xl="6">
        <el-form-item :label="$t('wheel.pattern.config.bonusMin')">
          <el-input-number
            v-model="form.bonus_min"
            :disabled="!!!form.bonus_enable"
            :min="10"
            :step="10"
            :placeholder="$t('wheel.pattern.config.bonusMin')"
            @change="updateCurrentBonus"
          />
        </el-form-item>
      </el-col>
      <el-col style="margin: 0 15px" :lg="10" :xl="6">
        <el-form-item :label="$t('wheel.pattern.config.bonusMax')">
          <el-input-number
            v-model="form.bonus_max"
            :disabled="!!!form.bonus_enable"
            :min="form.bonus_min+10"
            :step="10"
            :placeholder="$t('wheel.pattern.config.bonusMax')"
            @change="updateCurrentBonus"
          />
        </el-form-item>
      </el-col>
      <el-col style="margin: 0 15px" :lg="10" :xl="6">
        <el-form-item :label="$t('wheel.pattern.config.bonusCurrent')">
          <el-tag class="wheel-bonus-tag" effect="dark" type="danger">{{ form.bonus_current }}</el-tag>
        </el-form-item>
      </el-col>
      <el-col style="margin: 0 15px" :lg="10" :xl="6">
        <el-form-item :label="$t('wheel.pattern.config.bonusRate')">
          <el-input-number
            v-model="form.bonus_rate"
            :disabled="!!!form.bonus_enable"
            :min="1"
            :max="20"
            :placeholder="$t('wheel.pattern.config.bonusRate')"
          />
        </el-form-item>
      </el-col>
    </form-section>

    <!-- Location On Wheel -->
    <form-section :title="$t('wheel.pattern.config.locationOnWheel')">
      <el-col style="margin: 0 15px" :lg="10" :xl="5">
        <el-form-item
          :label="'Bonus: ' + form.bonus_rate + '% win'"
        >
          <el-input v-model="form.bonus_rate" disabled :placeholder="$t('wheel.pattern.config.selectPrize')" />

        </el-form-item>
      </el-col>
      <el-col v-for="index in prizes_location.length" :key="index" style="margin: 0 15px" :lg="10" :xl="5">
        <el-form-item
          :label="locationLabel(index)"
          :prop="locationProp('prizes_location', Number(index))"
          :rules="{
            required: true,
            message: $t('wheel.pattern.config.location') + ' ' + Number(index) + $t('validate.required'),
            trigger: 'blur'}"
        >
          <el-select
            v-model="form.prizes_location[(index).toString()]"
            filterable
            clearable
            :loading="isSearching"
            remote
            :remote-method="handleSearchPrize"
            :placeholder="$t('wheel.pattern.config.selectPrize')"
          >
            <el-option
              v-for="item in options"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
      </el-col>
    </form-section>

    <wheel-pattern-config-action :action="formAction" @handleUpdate="handleUpdate" @handleClose="handleClose" />

  </el-form>
</template>

<script>

import Resource from '@/api/resource';
import { validName } from '@/utils/validate';
import { nameToAlias } from '@/utils/helper';
import WheelPatternConfigAction from '@/views/wheel-pattern/components/WheelPatternConfigAction';
import FormSection from '@/views/wheel-pattern/components/FormSection';

const defaultForm = {
  name: '',
  alias: '',
  type: 'Text',
  prizes_location: [],
  prize_id: null,
  bonus_min: 50,
  bonus_max: 500,
  bonus_current: 0,
  bonus_fixed: 0,
  bonus_rate: 1,
  bonus_auto_increment: 1,
  bonus_enable: 1,
};

// const defaultLocation = {
//   1: null, 2: null, 3: null, 4: null, 5: null, 6: null,
//   7: null, 8: null, 9: null, 10: null, 11: null,
// };

const PrizeResource = new Resource('wheel/prizes');
const PatternResource = new Resource('wheel/patterns');

export default {
  name: 'WheelPatternConfig',
  components: { FormSection, WheelPatternConfigAction },
  props: {
    pattern: {
      type: Object,
      default: () => {
      },
    },
  },
  data() {
    const validateName = (rule, value, callback) => {
      if (value) {
        if (validName(value)) {
          callback();
        } else {
          callback(new Error(this.$t('wheel.prize.form.' + rule.field) + this.$t('validate.isInvalid')));
        }
      } else {
        callback();
      }
    };
    return {
      options: {},
      searchQuery: {
        limit: 20,
        page: 1,
      },
      prizes: [],
      form: {},
      formAction: '',
      prizes_location: new Array(11),
      isSearching: false,
      isUpdating: false,
      rules: {
        name: [{ validate: validateName }],
      },
    };
  },
  created() {
    if (this.pattern !== null) {
      this.formAction = 'edit';
      this.form = Object.assign({}, this.pattern);
      if (this.pattern.prizes_location == null) {
        this.form.prizes_location = [...this.prizes_location];
      } else {
        this.getPrizeOptions(this.pattern.prizes_location);
      }
    } else {
      this.formAction = 'create';
      this.form = Object.assign({}, defaultForm);
      this.form.prizes_location = [...this.prizes_location];
    }
    this.updateCurrentBonus(this.form.bonus_min);
  },
  methods: {
    handleSearchPrize(keyword) {
      if (keyword) {
        this.isSearching = true;
        setTimeout(() => {
          this.searchQuery.searchKeyword = keyword;
          PrizeResource.index(this.searchQuery).then(response => {
            if (response.status === 200) {
              this.prizes = response.data;
              this.options = this.prizes.filter(item => {
                return (item.name && item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1) ||
                    (item.more_info && item.more_info.toLowerCase().indexOf(keyword.toLowerCase()) > -1) ||
                    (item.type && item.type.toLowerCase().indexOf(keyword.toLowerCase()) > -1);
              });

              this.isSearching = false;
            }
          });
        }, 100);
      } else {
        this.options = [];
      }
    },
    handleClose() {
      this.$emit('onClose', false);
    },
    handleUpdate() {
      this.isUpdating = true;
      this.$refs['form'].validate(valid => {
        this.form.alias = nameToAlias(this.form.name);
        delete this.form.prizes;
        this.form.prizes_location[0] = null;
        if (valid) {
          if (this.pattern) {
            this.update();
          } else {
            this.create();
          }
        } else {
          return false;
        }
      });
      this.form = Object.assign({}, defaultForm);
      this.handleClose();
      this.isUpdating = false;
    },
    getPrizeOptions(ids) {
      if (ids) {
        this.searchQuery.ids = ids;
        PrizeResource.index(this.searchQuery).then(response => {
          if (response.status === 200) {
            this.prizes = response.data;
            this.options = this.prizes;
          }
        });
      }
    },
    create(){
      PatternResource.store(this.form)
        .then(response => {
          if (response.status === 200) {
            this.$notify({
              title: this.$t('status.success'),
              message: response.data.message + this.$t('notify.result.added'),
              type: 'success',
              duration: 2000,
            });
          }
        });
    },
    updateCurrentBonus(val) {
      this.form.bonus_current = window._.max([this.form.bonus_current, val]);
      this.form.bonus_current = window._.min([this.form.bonus_current, this.form.bonus_max]);
      this.form.bonus_current = window._.max([this.form.bonus_current, this.form.bonus_min]);
    },
    update(){
      PatternResource.update(this.pattern.id, this.form)
        .then(response => {
          if (response.status === 200) {
            // this.$notify({
            //   title: this.$t('status.success'),
            //   message: response.data.message + this.$t('notify.result.edited'),
            //   type: 'success',
            //   duration: 2000,
            // });
          }
        })
        .catch(err => {
          this.$notify({
            title: this.$t('status.failure'),
            message: err,
            type: 'error',
            duration: 2000,
          });
        });
    },
    handleNameChange(name) {
      this.form.pattern_alias = nameToAlias(name);
    },
    locationLabel(index) {
      if (!index) {
        throw new Error('Index is required');
      }
      return this.$t('wheel.pattern.config.location') + ': ' + Number(index);
    },
    locationProp(path, index) {
      if (!path) {
        throw new Error('path is required');
      }
      if (!index) {
        throw new Error('index is required');
      }
      return path + '.' + index;
    },
  },
};
</script>

<style scoped>
.wheel-pattern-config {
  background: #dedede;
  padding: 20px;
  margin: 15px;
  border-radius: 5px;
}
.wheel-bonus-tag {
  font-size: 24px;
  height: 40px;
  line-height: 40px;
  width: 100px;
  text-align: center;
}
</style>
