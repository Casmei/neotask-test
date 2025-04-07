module.exports = {
  "petstore-file-transfomer": {
    output: {
      mode: "split",
      target: "./api-request/api.ts",
      schemas: "./model",
      override: {
        mutator: {
          path: './api-request/mutator/custom-instance.ts',
          name: 'customInstance',
        },
      },
    },
    input: {
      target: "http://localhost:8080/docs/api.json",
    },
  },
};
