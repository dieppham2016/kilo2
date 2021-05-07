/* All validations should be defined here */

export function isExternal(path) {
  return /^(https?:|mailto:|tel:)/.test(path);
}

/**
 * Validate a valid URL
 * @param {String} textval
 * @return {Boolean}
 */
export function validURL(url) {
  const reg = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
  return reg.test(url);
}

/**
 * Validate a full-lowercase string
 * @return {Boolean}
 * @param {String} str
 */
export function validLowerCase(str) {
  const reg = /^[a-z]+$/;
  return reg.test(str);
}

/**
 * Validate a full-uppercase string
 * @return {Boolean}
 * @param {String} str
 */
export function validUpperCase(str) {
  const reg = /^[A-Z]+$/;
  return reg.test(str);
}

/**
 * Check if a string contains only alphabet
 * @param {String} str
 * @param {Boolean}
 */
export function validAlphabets(str) {
  const reg = /^[A-Za-z]+$/;
  return reg.test(str);
}

/**
 * Validate an email address
 * @param {String} email
 * @return {Boolean}
 */
export function validEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

export function validName(name) {
  const regex = /^([a-zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]+\s)*[a-zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]+$/;
  return regex.test(name.toLowerCase());
}

export function validNameWithNumber(name) {
  const regex = /^([a-zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]+[\s-_])*[a-zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ0-9]+$/;
  return regex.test(name);
}

export function trimName(name) {
  const regex = /[^a-zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ\s-_0-9]+$/;
  return name.replace(regex, '');
}

export function validUid(input) {
  const regex = /^([0-9]{10})*$/;
  return regex.test(input);
}

export function validStaffCode(input) {
  const regex = /^C[0-9]{5}$/;
  return regex.test(input);
}

export function validUsername(input) {
  const regex = /^([a-z]+[_])*[a-z0-9]+$/;
  return regex.test(input);
}

export function validPhone(input) {
  const regex = /^[0-9]{10, 11}*$/;
  return regex.test(input);
}

export function validHEXColor(color) {
  const regex = /^#(?:(?:[\da-f]{3}){1,2}|(?:[\da-f]{4}){1,2})$/i;
  return regex.test(color);
}
