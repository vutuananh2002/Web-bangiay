const randomColor = () => {
    return `#${(Math.floor(Math.random() * 0xffffff)).toString(16)}`;
}

export { randomColor };