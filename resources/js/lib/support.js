export const types = {
    tugas_panwas: "S-Tugas TTD Ketua",
    keluar_panwas: "S-Keluar TTD Ketua",
    tugas_sekretariat: "S-Keluar Kesekretariatan",
};

export const types_options = Object.keys(types).map((k) => ({
    code: k,
    label: types[k],
}));
