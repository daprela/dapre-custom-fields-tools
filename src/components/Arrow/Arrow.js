import React, { createRef, useEffect } from "react";
import styles from "./arrow.module.scss";

function Arrow({ className, title, content, arrowClick: harrowClickProp }) {
  const arrowRef = createRef();

  function handleArrowClick(e) {
    let index;

    if (content === "-") {
      index = e.target.parentElement.nextElementSibling.dataset.index;
    } else {
      index = e.target.parentElement.dataset.index;
    }
    harrowClickProp(index, content);
  }

  return (
    <button
      className={`${className} ${styles.arrow}`}
      title={title}
      ref={arrowRef}
      onClick={handleArrowClick}
    >
      {content}
    </button>
  );
}

export default Arrow;
