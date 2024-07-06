import { getNestedValue, setNestedValue } from "../../utils";
import { reactive } from "./reactive";

const evaluateExpression = (expression, context) => {
  try {
    return new Function(
      "context",
      "with(context) { return " + expression + "; }"
    ).call(context, context);
  } catch (error) {
    console.error("Error evaluating expression:", expression, error);
    return null;
  }
};

const bindElement = (element, expression, context, handler) => {
  const evaluate = () => {
    const value = evaluateExpression(expression, context);
    if (element.hasAttribute("x-if")) {
      element.style.display = value ? "" : "none";
    } else if (element.hasAttribute("x-show")) {
      element.style.visibility = value ? "visible" : "hidden";
    } else if (element.hasAttribute("x-text")) {
      element.textContent = value != null ? value.toString() : "";
    }
  };

  handler.subscribe(evaluate);
  evaluate();
};

const bindInterpolation = (element, context, handler) => {
  const originalContent = element.innerHTML;

  const interpolate = () => {
    element.innerHTML = originalContent.replace(
      /{{\s*(\S+?)\s*}}/g,
      (match, p1) => {
        return getNestedValue(context, p1) || "";
      }
    );
  };

  handler.subscribe(interpolate);
  interpolate();
};

export const bindDirectives = (data) => {
  const handler = reactive(data);

  document.querySelectorAll("[x-if]").forEach((element) => {
    const expression = element.getAttribute("x-if");
    bindElement(element, expression, handler.proxy, handler);
  });

  document.querySelectorAll("[x-show]").forEach((element) => {
    const expression = element.getAttribute("x-show");
    bindElement(element, expression, handler.proxy, handler);
  });

  document.querySelectorAll("[x-text]").forEach((element) => {
    const expression = element.getAttribute("x-text");
    bindElement(element, expression, handler.proxy, handler);
  });

  document.querySelectorAll("[x-model]").forEach((element) => {
    const expression = element.getAttribute("x-model");

    element.value = getNestedValue(handler.proxy, expression);

    element.addEventListener("input", (event) => {
      setNestedValue(handler.proxy, expression, event.target.value);
      document.body.dispatchEvent(new CustomEvent("stateChange")); // Trigger stateChange event
    });

    const update = () => {
      element.value = getNestedValue(handler.proxy, expression);
    };

    handler.subscribe(update);
  });

  document.querySelectorAll("*").forEach((element) => {
    traverseAndBind(element, handler.proxy, handler);
  });
};

const traverseAndBind = (element, context, handler) => {
  const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, {
    acceptNode(node) {
      if (node.nodeValue.includes("{{")) {
        return NodeFilter.FILTER_ACCEPT;
      }
      return NodeFilter.FILTER_REJECT;
    },
  });

  let node;
  while ((node = walker.nextNode())) {
    bindInterpolation(node.parentElement, context, handler);
  }
};
