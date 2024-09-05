const router = new Router();

router.addRoute("/", async () => {
    const response = await fetch("/content/home");
    const content = await response.text();
    return content;
});

router.addRoute("/results/polling_units", async () => {
    const response = await fetch("/content/results/polling_units");
    const content = await response.text();
    return content;
});

router.addRoute("/results/polling_units/add", async () => {
    const response = await fetch("/content/results/polling_units/add");
    const content = await response.text();
    return content;
});

router.addRoute("/results/display", async () => {
    const response = await fetch("/content/results/display" + location.search);
    const content = await response.text();
    return content;
});

router.addRoute("/results/lga", async () => {
    const response = await fetch("/content/results/lga");
    const content = await response.text();
    return content;
});

router.addRoute("/results/lga/display", async () => {
    const response = await fetch("/content/results/lga/display" + location.search);
    const content = await response.text();
    return content;
});

router.addRoute("/results/polling_units/add", async () => {
    const response = await fetch("/content/results/polling_units/add");
    const content = await response.text();
    return content;
});


const handleRouteRender = (path) => {

    

};
  
router.setAfterRouteRender(handleRouteRender);