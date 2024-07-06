export const getNestedValue = (obj, path) => {
  return path.split(".").reduce((acc, part) => acc && acc[part], obj);
};

export const setNestedValue = (obj, path, value) => {
  const parts = path.split(".");
  const last = parts.pop();
  const target = parts.reduce((acc, part) => acc && acc[part], obj);
  if (target && last) {
    target[last] = value;
  }
};
