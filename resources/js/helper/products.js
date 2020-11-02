// set session variables for products and categories for vuex

export function getCategoryId(){
  const categoryStr = localStorage.getItem("categoryId");
  if(!categoryStr) {
    return null;
  }
  return JSON.parse(categoryStr);
}
