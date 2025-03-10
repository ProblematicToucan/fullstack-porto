document.addEventListener("alpine:init", () => {
    Alpine.data("navigationMenu", () => ({
        navigationMenuOpen: false,
        navigationMenu: "",
        navigationMenuCloseDelay: 200,
        navigationMenuCloseTimeout: null,

        navigationMenuLeave() {
            this.navigationMenuCloseTimeout = setTimeout(() => {
                this.navigationMenuClose();
            }, this.navigationMenuCloseDelay);
        },

        navigationMenuReposition(navElement) {
            this.navigationMenuClearCloseTimeout();
            this.$refs.navigationDropdown.style.left = navElement.offsetLeft + "px";
            this.$refs.navigationDropdown.style.marginLeft = navElement.offsetWidth / 2 + "px";
        },

        navigationMenuClearCloseTimeout() {
            clearTimeout(this.navigationMenuCloseTimeout);
        },

        navigationMenuClose() {
            this.navigationMenuOpen = false;
            this.navigationMenu = "";
        }
    }));
});
