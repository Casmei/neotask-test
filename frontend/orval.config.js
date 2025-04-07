module.exports = {
  "petstore-file-transfomer": {
    output: {
      mode: "split",
      target: "./api-request/api.ts",
      schemas: "./model",
      client: 'fetch',
      baseUrl: 'http://localhost:8080/api',
    },
    input: {
      target: "http://localhost:8080/docs/api.json",
    },
  },
};
