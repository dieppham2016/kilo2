import request from '@/utils/request';

/**
 * Simple RESTful resource class
 */
class GameResource {
  constructor(uri) {
    this.uri = uri;
  }
  degrees() {
    return request({
      url: '/' + this.uri,
      method: 'get',
    });
  }
  show(id) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'get',
    });
  }
  store(resource) {
    return request({
      url: '/' + this.uri,
      method: 'post',
      data: resource,
    });
  }
  update(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'put',
      data: resource,
    });
  }
}

export { GameResource as default };
