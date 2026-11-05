<style>
@media (min-width: 601px) {
    .team-page .team-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
        gap: 12px !important;
        align-items: start;
    }
    .team-page .team-card {
        display: grid;
        grid-template-columns: 56px minmax(0, 1fr);
        gap: 3px 12px;
        align-items: start;
        min-height: 0;
        padding: 14px !important;
        border-radius: 12px;
    }
    .team-page .team-card > .team-photo {
        grid-column: 1;
        grid-row: 1 / span 3;
        width: 56px !important;
        height: 56px !important;
        border-radius: 9px;
    }
    .team-page .team-card > h3 {
        grid-column: 2;
        grid-row: 1;
        min-height: 0;
        margin: 0;
        font-size: 14px !important;
        line-height: 1.4;
    }
    .team-page .team-card > .team-role {
        grid-column: 2;
        grid-row: 2;
        min-height: 0;
        margin: 0;
        font-size: 12px;
        line-height: 1.45;
    }
    .team-page .team-card > .team-status {
        grid-column: 2;
        grid-row: 3;
        justify-self: start;
        padding: 2px 7px;
        line-height: 1.5;
    }
    .team-page .team-card > .team-detail {
        grid-column: 1 / -1;
        grid-row: 4;
        min-width: 0;
        margin: 7px 0 0;
        padding-top: 7px;
    }
    .team-page .team-card > .team-detail > summary {
        min-height: 24px;
        line-height: 24px;
    }
    .team-page .team-detail h4 { margin: 10px 0 6px; }
}
</style>
