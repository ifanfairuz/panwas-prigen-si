export const getGradient =
    (...colors) =>
    ({ chart }) => {
        const gradient = chart.ctx.createLinearGradient(0, 0, 0, 450);
        for (const color of colors) {
            gradient.addColorStop(...color);
        }
        return gradient;
    };
