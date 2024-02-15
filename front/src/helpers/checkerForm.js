const checkFieldNotEmpty = (field) => {
  return field !== '' && field !== null && field !== undefined;
};

const checkFieldLength = (field, length) => {
  return field.length >= length;
};

const checkFieldEmail = (field) => {
  const reg = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  return reg.test(field);
};

const checkFieldPassword = (field) => {
  const reg = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/;
  return reg.test(field);
};

const checkFieldsNotEmpty = (fields) => {
  let result = true;
  fields.forEach((field) => {
    if (!checkFieldNotEmpty(field)) {
      result = false;
    }
  });

  return result;
};

const checkPhone = (phone) => {
  return /^[0-9]{10}$/.test(phone) && phone.length === 10;
};

const checkInputText = (text) => {
  return /^[a-zA-Z]+$/.test(text) && text.length > 0 && text.length < 55;
};

export {
  checkFieldNotEmpty,
  checkFieldLength,
  checkFieldEmail,
  checkFieldPassword,
  checkFieldsNotEmpty,
  checkPhone,
  checkInputText,
};
