import request from '@/utils/request';

/**
 * Simple RESTful resource class
 */
class Resource {
  constructor(uri) {
    this.uri = uri;
  }
  index(query) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      params: query,
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
  destroy(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'delete',
      data: resource,
    });
  }
  destroys(ids) {
    return request({
      url: '/' + this.uri,
      method: 'delete',
      data: ids,
    });
  }
}

export { Resource as default };
