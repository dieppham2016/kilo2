<template>
  <el-row>
    <sticky :class-name="'sub-navbar draft'">
      <el-row type="flex" justify="left">
        <el-select
          v-model="value"
          style="margin-left: .75rem;"
          filterable
          clearable
          placeholder="Lọc bộ sưu tập"
        >
          <el-option
            v-for="item in list"
            :key="item.id"
            :label="item.display_name"
            :value="item.display_name"
          />
        </el-select>
        <div style="margin-left: auto">
          <el-button
            style="margin-left: auto;"
            type="primary"
            @click="openDialog = true"
          >
            {{ $t('actions.createCollection') }}
          </el-button>
        </div>
      </el-row>
    </sticky>
    <el-row v-if="list.length <= 0" type="flex" justify="center">
      <h3>{{ $t('status.noData') }}</h3>
    </el-row>

    <detail-form v-if="openDialog" @onClose="handleCloseFormCreate" />

    <el-row style="padding: 0.5rem;">
      <el-row v-for="items in list" :key="items.id" class="gallery-wrapper">
        <div v-show="value === '' || value === items.display_name">
          <div class="gallery-name">
            <div class="gallery-name__item">
              <span>{{ items.display_name }}</span>
              <span style="text-shadow: 1px 2px 5px #1c8cec;">({{ items.count }})</span>
            </div>
            <div style="display: inline-block">
              <el-row v-if="editCollection === items.id" style="display: inline-block;">
                <el-form
                  ref="form"
                  :model="form"
                  :rules="rules"
                >
                  <el-form-item style="margin-bottom: 0; text-shadow: none" prop="display_name">
                    <el-input
                      v-model="form.display_name"
                      :placeholder="$t('form.media.collection_name')"
                      maxlength="20"
                      show-word-limit
                      clearable
                    />
                  </el-form-item>
                </el-form>
              </el-row>
              <el-tooltip
                class="item"
                effect="dark"
                :content="(editCollection !== items.id) ? $t('actions.edit') + ' ' + items.display_name : $t('actions.confirm')"
                placement="top-start"
              >
                <el-button
                  style="margin-left: 0.5rem;"
                  :icon="editCollection === items.id ? 'el-icon-check' : 'el-icon-edit'"
                  circle
                  @click="toggleEditCollectionName(items)"
                />
              </el-tooltip>
              <el-tooltip
                class="item"
                effect="dark"
                :content="$t('actions.delete') + ' ' + items.display_name"
                placement="top-start"
              >
                <el-popconfirm
                  confirm-button-text="Xác nhận"
                  cancel-button-text="Đóng"
                  cancel-button-type="danger"
                  icon="el-icon-delete"
                  icon-color="red"
                  title="Hành động này sẽ xóa vĩnh viễn."
                  @onConfirm="handleDeleteCollection(items)"
                >
                  <el-button slot="reference" icon="el-icon-delete" circle />
                </el-popconfirm>
              </el-tooltip>

            </div>
          </div>
          <gallery-image
            list-type="picture-card"
            :files="items.files"
            :original-collection="items.original_name"
            :handle-preview="handlePictureCardPreview"
            :on-upload-success="getImages"
            :on-remove="handleRemoveImage"
          />
        </div>

      </el-row>

      <el-dialog
        :visible.sync="dialogPreviewPictureVisible"
        custom-class="bg-transparent"
      >
        <el-row type="flex" justify="center">
          <el-col :md="{span: 1}">
            <el-button
              v-if="showPrevBtn"
              class="el-carousel__arrow btn-arrow__left"
              type="info"
              icon="el-icon-arrow-left"
              circle
              @click="prevSlide"
            />
          </el-col>
          <el-col :md="{span: 22}">
            <el-carousel
              ref="slideImage"
              :autoplay="false"
              :loop="false"
              :initial-index="activeItem"
              arrow="never"
              indicator-position="none"
              trigger="click"
              :height="carouselHeight"
              @change="handleCarouselChange"
            >
              <el-carousel-item v-for="item in collectionToShow" :key="item.id" ref="images">
                <img :ref="'item_' + item.id" width="100%" :src="item.url" alt="">
              </el-carousel-item>
            </el-carousel>
          </el-col>

          <el-col :md="{span: 1}">
            <el-button
              v-if="showNextBtn"
              class="el-carousel__arrow btn-arrow__right"
              type="info"
              icon="el-icon-arrow-right"
              circle
              @click="nextSlide"
            />
          </el-col>
        </el-row>
      </el-dialog>
    </el-row>
  </el-row>
</template>

<script>
import Sticky from '@/components/Sticky';
import Resource from '@/api/resource';
import DetailForm from '@/views/media/components/DetailForm';
import GalleryImage from '@/components/Gallery/GalleryImage';
import { trimName, validNameWithNumber } from '@/utils/validate';

const MediaResource = new Resource('media');

// const defaultForm = {
//   collection_name: '',
//   filesImage: {},
// };

export default {
  name: 'List',
  components: { GalleryImage, DetailForm: DetailForm, Sticky },
  props: {},
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('form.media.' + rule.field) + this.$t('validate.required')));
      } else if (!validNameWithNumber(value.toString().toLowerCase())) {
        this.form.display_name = trimName(value);
      } else {
        callback();
      }
    };
    return {
      rules: {
        display_name: [{ validator: validateRequire }],
      },
      listQuery: {
        page: 1,
        limit: 10,
      },
      list: [],
      total: 0,
      listLoading: true,
      openDialog: false,
      dialogImageUrl: '',
      dialogPreviewPictureVisible: false,
      carouselHeight: null,
      activeItem: 0,
      currentCollectionImageId: null,
      collectionToShow: [],
      showPrevBtn: false,
      showNextBtn: false,
      form: {
        display_name: '',
      },
      editCollection: null,
      value: '',
    };
  },
  watch: {
    activeItem(val) {
      if (this.$refs.slideImage) {
        this.$refs.slideImage.setActiveItem(val);
      }
    },
    currentCollectionImageId(id) {
      this.$nextTick(() => {
        this.carouselHeight = this.$refs['item_' + id][0].clientHeight + 'px';
      });
    },
  },
  created() {
    this.getImages();
  },
  methods: {
    async getImages(collection, type) {
      const option = {
        collection_name: collection,
        type: 'image',
      };
      this.listLoading = true;
      const { data } = await MediaResource.index(option);
      this.list = data;
      this.listLoading = false;
      console.log(data);
    },
    handleDeleteCollection(collection) {
      MediaResource.destroy(collection.id, { level: 'collection' })
        .then(response => {
          if (response.status === 200) {
            this.getImages();
            this.$notify({
              title: this.$t('status.success'),
              message: this.$t('notify.deleted'),
              type: 'success',
              duration: 2000,
            });
            this.getImages();
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
    handleRemoveImage(file, collection) {
      const media = {
        level: 'image',
        original_name: collection,
      };
      MediaResource.destroy(file.id, media)
        .then(response => {
          if (response.status === 200) {
            this.getImages();
            this.$notify({
              title: this.$t('status.success'),
              message: this.$t('notify.deleted'),
              type: 'success',
              duration: 2000,
            });
            this.getImages();
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
    toggleEditCollectionName(collection) {
      if (this.editCollection === collection.id) {
        MediaResource.update(collection.id, this.form)
          .then(response => {
            if (response.status === 200) {
              this.getImages();
              this.$notify({
                title: this.$t('status.success'),
                message: response.message,
                type: 'success',
                duration: 2000,
              });
            } else if (response.status === 204) {
              this.$notify({
                title: this.$t('status.failure'),
                message: response.message,
                type: 'error',
                duration: 2000,
              });
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
        this.editCollection = null;
      } else {
        this.editCollection = collection.id;
        this.form.display_name = collection.display_name;
      }
    },
    handleCloseFormCreate(val) {
      this.openDialog = val;
      this.getImages();
    },
    handleCarouselChange(index) {
      this.showTransferButton(index);
      this.currentCollectionImageId = this.findCurrentCollectionId(this.collectionToShow, index);
    },
    handlePictureCardPreview(file, fileList) {
      this.currentCollectionImageId = file.id;
      this.dialogImageUrl = file.url;
      this.dialogPreviewPictureVisible = true;
      this.collectionToShow = fileList;
      this.activeItem = fileList.map(e => {
        return e.id;
      }).indexOf(this.currentCollectionImageId);
      this.showTransferButton(this.activeItem);
    },
    findCurrentCollectionId(list, id) {
      return list[id].id;
    },
    nextSlide() {
      if (this.activeItem < this.collectionToShow.length) {
        this.$refs.slideImage.next();
      }
    },
    prevSlide() {
      if (this.activeItem > 0) {
        this.$refs.slideImage.prev();
      }
    },
    showTransferButton(index) {
      this.showPrevBtn = index > 0;
      this.showNextBtn = index < this.collectionToShow.length - 1;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
