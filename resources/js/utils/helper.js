export function ifNull(value, replace) {
  if (!replace) {
    throw new Error('replace is required.');
  }
  return value === null ? replace : value;
}

export function nameToAlias(name) {
  let alias = '';
  alias = name.toLowerCase();
  alias = alias.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
  alias = alias.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
  alias = alias.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
  alias = alias.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
  alias = alias.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
  alias = alias.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
  alias = alias.replace(/đ/gi, 'd');
  alias = alias.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
  alias = alias.replace(/ /gi, '-');
  alias = alias.replace(/\-+/g, '-');
  alias = '@' + alias + '@';
  alias = alias.replace(/\@\-|\-\@|\@/gi, '');

  return alias;
}
