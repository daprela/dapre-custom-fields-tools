/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope */
import React, { StrictMode } from "react";
import { render } from "react-dom";
/* eslint-disable import/extensions */
import TabOptionsMeta from "./tab-options-meta.js";
import TabUsersMeta from "./tab-users-meta.js";
import TabPostsMeta from "./tab-posts-meta.js";

render(
  <StrictMode>
    <TabOptionsMeta />
  </StrictMode>,
  document.querySelector("#optionsMetaSection")
);
render(
  <StrictMode>
    <TabUsersMeta />
  </StrictMode>,
  document.querySelector("#usersMetaSection")
);
render(
  <StrictMode>
    <TabPostsMeta />
  </StrictMode>,
  document.querySelector("#postsMetaSection")
);
