// @ts-ignore
const Routing = window.Routing;

export const generateRoute = (routeName: string, params: any = undefined) => {
    return Routing.generate(routeName, params);
};