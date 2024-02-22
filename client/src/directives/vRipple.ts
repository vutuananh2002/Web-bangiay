const createRipple = (el: HTMLElement, binding: any, event: MouseEvent): void => {
    const rippleEl: HTMLSpanElement = document.createElement<"span">("span");
    let currentDiameter = 1;
    let currentOpacity = binding.value ? binding.value.opacity : 0.5;
    
    const applyRippleStyle = (): void => {
        const elCoordinates = el.getBoundingClientRect();
        const offsetX = event.clientX - elCoordinates.x;
        const offsetY = event.clientY - elCoordinates.y;

        rippleEl.style.position = "absolute";
        rippleEl.style.borderRadius = "50%";
        rippleEl.style.backgroundColor = (binding.value && binding.value.background) ? binding.value.background : "rgba(255,255,255,0.5)";
        rippleEl.style.width = "0.5rem";
        rippleEl.style.height = "0.5rem";
        rippleEl.style.top = `${offsetY}px`;
        rippleEl.style.left = `${offsetX}px`;
        rippleEl.classList.add("ripple");

        const rp = event.target.getElementsByClassName("ripple")[0];

        if (rp) {
            rippleEl.remove();
        }

        event.target.appendChild(rippleEl);
    }

    const animateRipple = (): void => {
        const maxDiameter = binding.value ? +binding.value.diameter : 50;
        if (currentDiameter <= maxDiameter) {
            currentDiameter++;
            currentOpacity -= currentOpacity / maxDiameter;
            rippleEl.style.transform = `scale(${currentDiameter})`;
            rippleEl.style.opacity = `${currentOpacity}`;
        } else {
            rippleEl.remove();
            clearInterval(animate);
        }
    }

    const animate = setInterval(animateRipple, 15);
    applyRippleStyle();
}

const vRipple = {
    mounted: (el: HTMLElement, binding: any) => {
        el.style.position = "relative";
        el.style.overflow = "hidden";
        el.addEventListener<"click">("click", e => createRipple(el, binding, e));
    },

    unmounted: (el: HTMLElement, binding: any) => {
        el.removeEventListener<"click">("click", e => createRipple(el, binding, e));
    }
}

export default vRipple;